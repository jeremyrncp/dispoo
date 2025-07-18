<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\PaymentRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\Event;
use Stripe\Invoice;
use Stripe\PaymentIntent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription')]
    public function index(SubscriptionRepository $subscriptionRepository, PaymentRepository $paymentRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $subscription = $subscriptionRepository->findOneBy(["owner" => $user]);

        $payments = $paymentRepository->findBy(["owner" => $user], ["createdAt" => "DESC"]);

        return $this->render('subscription/index.html.twig', [
            'user' => $user,
            'subscription' => $subscription,
            'payments' => $payments
        ]);
    }


    #[Route('/create-subscription', name: 'create_subscription', methods: ['POST'])]
    public function createSubscription(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $paymentMethod = $data['paymentMethod'];

        // 1. Créer un client
        $customer = \Stripe\Customer::create([
            'email' => $email,
            'payment_method' => $paymentMethod,
            'invoice_settings' => [
                'default_payment_method' => $paymentMethod,
            ],
        ]);

        // 2. Créer un abonnement
        $subscriptionStripe = \Stripe\Subscription::create([
            'customer' => $customer->id,
            'items' => [[ 'price' => $_ENV['STRIPE_PRICE_ID'] ]],
            'payment_behavior' => 'default_incomplete'
        ]);

        $subscription = new Subscription();
        $subscription->setCreatedAt(new \DateTime())
                     ->setOwner($user)
                     ->setSubscriptionStripeId($subscriptionStripe->id)
                     ->setActive(true);


        /** @var Invoice $invoice */
        $payments = $subscriptionStripe->latest_invoice->payments;

        if ($payments !== null) {
            /** @var PaymentIntent $paymentIntent */
            $paymentIntent = end($payments);
        }


        return $this->json([
            'subscriptionId' => $subscriptionStripe->id,
            'clientSecret' =>         $paymentIntent->client_secret,
        ]);
    }

    #[Route('/webhook/stripe', name: 'stripe_webhook', methods: ['POST'])]
    public function handleStripeWebhook(Request $request, LoggerInterface $logger, SubscriptionRepository $subscriptionRepository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->headers->get('stripe-signature');
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'];

        try {
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );

            $logger->info('✅ Webhook Stripe reçu : '.$event->type);

            switch ($event->type) {
                case 'invoice.paid':
                    /** @var Invoice $invoice */
                    $invoice = $event->data->object;

                    $user = $userRepository->findOneBy(["email" => $invoice->customer->email]);
                    $payment = new Payment();
                    $payment->setCreatedAt(new \DateTime())
                            ->setState(Payment::STATE_ACCEPTED)
                            ->setAmountCents($invoice->amount_paid)
                            ->setOwner($user);
                    $entityManager->persist($payment);
                    $entityManager->flush();
                break;

                case 'invoice.payment_failed':
                    /** @var Invoice $invoice */
                    $invoice = $event->data->object;

                    $user = $userRepository->findOneBy(["email" => $invoice->customer->email]);
                    $payment = new Payment();
                    $payment->setCreatedAt(new \DateTime())
                        ->setState(Payment::STATE_REJECTED)
                        ->setAmountCents($invoice->amount_paid)
                        ->setOwner($user);
                    $entityManager->persist($payment);
                    $entityManager->flush();
                break;

                case 'customer.subscription.deleted	':
                    /** @var \Stripe\Subscription $subscription */
                    $subscriptionStripe = $event->data->object;

                    /** @var Subscription $subscription */
                    $subscription = $subscriptionRepository->findOneBy(["subscriptionStripeId" => $subscriptionStripe->id]);
                    $entityManager->remove($subscription);
                    $entityManager->flush();
                    break;


                case 'customer.subscription.paused':
                    /** @var \Stripe\Subscription $subscription */
                    $subscriptionStripe = $event->data->object;

                    /** @var Subscription $subscription */
                    $subscription = $subscriptionRepository->findOneBy(["subscriptionStripeId" => $subscriptionStripe->id]);
                    $subscription->setActive(false);
                    $entityManager->flush();
                    break;

                case 'customer.subscription.resumed':
                    /** @var \Stripe\Subscription $subscription */
                    $subscriptionStripe = $event->data->object;

                    /** @var Subscription $subscription */
                    $subscription = $subscriptionRepository->findOneBy(["subscriptionStripeId" => $subscriptionStripe->id]);
                    $subscription->setActive(true);
                    $entityManager->flush();
                    break;

                default:
                    $logger->info('ℹ️ Événement ignoré : '.$event->type);
            }

            return new Response('Webhook traité', 200);

        } catch (\UnexpectedValueException $e) {
            return new Response('⚠️ Payload invalide', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return new Response('⚠️ Signature invalide', 400);
        }
    }

    #[Route('/subscription/trial', name: 'app_subscription_trial')]
    public function trial(EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (is_null($user->getTrialEndedAt())) {
            $user->setTrialEndedAt((new \DateTime())->modify("+1 month"));
            $entityManager->flush();

            $this->addFlash("message", "Vous bénéficiez désormais de l'offre d'essai gratuit");
            return $this->redirectToRoute("app_subscription");
        }

        $this->addFlash("error", "Vous avez déjà bénéficié de l'offre d'essai gratuit");
        return $this->redirectToRoute("app_subscription");
    }
}
