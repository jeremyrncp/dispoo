object(Stripe\Invoice)#6254 (73) {
["id"]=>
string(27) "in_1RmJOMPJ5RguOnPYDiQZMeoz"
["object"]=>
string(7) "invoice"
["account_country"]=>
string(2) "FR"
["account_name"]=>
string(28) "Environnement de test Dispoo"
["account_tax_ids"]=>
NULL
["amount_due"]=>
int(1500)
["amount_overpaid"]=>
int(0)
["amount_paid"]=>
int(0)
["amount_remaining"]=>
int(1500)
["amount_shipping"]=>
int(0)
["application"]=>
NULL
["attempt_count"]=>
int(0)
["attempted"]=>
bool(false)
["auto_advance"]=>
bool(false)
["automatic_tax"]=>
object(Stripe\StripeObject)#355 (5) {
["disabled_reason"]=>
NULL
["enabled"]=>
bool(false)
["liability"]=>
NULL
["provider"]=>
NULL
["status"]=>
NULL
}
["automatically_finalizes_at"]=>
NULL
["billing_reason"]=>
string(19) "subscription_create"
["collection_method"]=>
string(20) "charge_automatically"
["created"]=>
int(1752864742)
["currency"]=>
string(3) "eur"
["custom_fields"]=>
NULL
["customer"]=>
string(18) "cus_ShiqDVpWtjSNQc"
["customer_address"]=>
NULL
["customer_email"]=>
string(23) "contact@gaultierweb.com"
["customer_name"]=>
NULL
["customer_phone"]=>
NULL
["customer_shipping"]=>
NULL
["customer_tax_exempt"]=>
string(4) "none"
["customer_tax_ids"]=>
array(0) {
}
["default_payment_method"]=>
NULL
["default_source"]=>
NULL
["default_tax_rates"]=>
array(0) {
}
["description"]=>
NULL
["discounts"]=>
array(0) {
}
["due_date"]=>
NULL
["effective_at"]=>
int(1752864742)
["ending_balance"]=>
int(0)
["footer"]=>
NULL
["from_invoice"]=>
NULL
["hosted_invoice_url"]=>
string(159) "https://invoice.stripe.com/i/acct_1RmG9VPJ5RguOnPY/test_YWNjdF8xUm1HOVZQSjVSZ3VPblBZLF9TaGlxR3ZRQlVSeUR2Tld3aWRiYXBtR3NVbVU3aHNnLDE0MzQwNTU0Mw02007IPmCRyT?s=ap"
["invoice_pdf"]=>
string(165) "https://pay.stripe.com/invoice/acct_1RmG9VPJ5RguOnPY/test_YWNjdF8xUm1HOVZQSjVSZ3VPblBZLF9TaGlxR3ZRQlVSeUR2Tld3aWRiYXBtR3NVbVU3aHNnLDE0MzQwNTU0Mw02007IPmCRyT/pdf?s=ap"
["issuer"]=>
object(Stripe\StripeObject)#338 (1) {
["type"]=>
string(4) "self"
}
["last_finalization_error"]=>
NULL
["latest_revision"]=>
NULL
["lines"]=>
object(Stripe\Collection)#353 (5) {
["object"]=>
string(4) "list"
["data"]=>
array(1) {
[0]=>
object(Stripe\InvoiceLineItem)#325 (17) {
["id"]=>
string(27) "il_1RmJOMPJ5RguOnPYMnn0JLGG"
["object"]=>
string(9) "line_item"
["amount"]=>
int(1500)
["currency"]=>
string(3) "eur"
["description"]=>
string(44) "1 × Abonnement Dispoo (at €15.00 / month)"
["discount_amounts"]=>
array(0) {
}
["discountable"]=>
bool(true)
["discounts"]=>
array(0) {
}
["invoice"]=>
string(27) "in_1RmJOMPJ5RguOnPYDiQZMeoz"
["livemode"]=>
bool(false)
["metadata"]=>
object(Stripe\StripeObject)#505 (0) {
}
["parent"]=>
object(Stripe\StripeObject)#503 (3) {
["invoice_item_details"]=>
NULL
["subscription_item_details"]=>
object(Stripe\StripeObject)#487 (5) {
["invoice_item"]=>
NULL
["proration"]=>
bool(false)
["proration_details"]=>
object(Stripe\StripeObject)#476 (1) {
["credited_items"]=>
NULL
}
["subscription"]=>
string(28) "sub_1RmJOLPJ5RguOnPYVhk9IWx6"
["subscription_item"]=>
string(17) "si_Shiqc2GS6brYEx"
}
["type"]=>
string(25) "subscription_item_details"
}
["period"]=>
object(Stripe\StripeObject)#495 (2) {
["end"]=>
int(1755543141)
["start"]=>
int(1752864741)
}
["pretax_credit_amounts"]=>
array(0) {
}
["pricing"]=>
object(Stripe\StripeObject)#485 (3) {
["price_details"]=>
object(Stripe\StripeObject)#454 (2) {
["price"]=>
string(30) "price_1RmGE3PJ5RguOnPYCFm2klYu"
["product"]=>
string(19) "prod_ShfZ0oE5nUI3Fw"
}
["type"]=>
string(13) "price_details"
["unit_amount_decimal"]=>
string(4) "1500"
}
["quantity"]=>
int(1)
["taxes"]=>
array(0) {
}
}
}
["has_more"]=>
bool(false)
["total_count"]=>
int(1)
["url"]=>
string(46) "/v1/invoices/in_1RmJOMPJ5RguOnPYDiQZMeoz/lines"
}
["livemode"]=>
bool(false)
["metadata"]=>
object(Stripe\StripeObject)#477 (0) {
}
["next_payment_attempt"]=>
NULL
["number"]=>
string(13) "3FCHGQLU-0014"
["on_behalf_of"]=>
NULL
["parent"]=>
object(Stripe\StripeObject)#513 (3) {
["quote_details"]=>
NULL
["subscription_details"]=>
object(Stripe\StripeObject)#434 (2) {
["metadata"]=>
object(Stripe\StripeObject)#373 (0) {
}
["subscription"]=>
string(28) "sub_1RmJOLPJ5RguOnPYVhk9IWx6"
}
["type"]=>
string(20) "subscription_details"
}
["payment_settings"]=>
object(Stripe\StripeObject)#442 (3) {
["default_mandate"]=>
NULL
["payment_method_options"]=>
NULL
["payment_method_types"]=>
NULL
}
["period_end"]=>
int(1752864741)
["period_start"]=>
int(1752864741)
["post_payment_credit_notes_amount"]=>
int(0)
["pre_payment_credit_notes_amount"]=>
int(0)
["receipt_number"]=>
NULL
["rendering"]=>
NULL
["shipping_cost"]=>
NULL
["shipping_details"]=>
NULL
["starting_balance"]=>
int(0)
["statement_descriptor"]=>
NULL
["status"]=>
string(4) "open"
["status_transitions"]=>
object(Stripe\StripeObject)#432 (4) {
["finalized_at"]=>
int(1752864742)
["marked_uncollectible_at"]=>
NULL
["paid_at"]=>
NULL
["voided_at"]=>
NULL
}
["subtotal"]=>
int(1500)
["subtotal_excluding_tax"]=>
int(1500)
["test_clock"]=>
NULL
["total"]=>
int(1500)
["total_discount_amounts"]=>
array(0) {
}
["total_excluding_tax"]=>
int(1500)
["total_pretax_credit_amounts"]=>
array(0) {
}
["total_taxes"]=>
array(0) {
}
["webhooks_delivered_at"]=>
NULL
}