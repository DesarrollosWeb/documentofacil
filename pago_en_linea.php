<?php
//region Includes
include_once 'gestoria/constants.php';
include_once 'gestoria/Procedure.php';
include_once 'gestoria/Db.php';
include_once 'gestoria/Payment.php';
include_once 'gestoria/vendor/autoload.php';
include_once "wp-load.php";
//endregion
if (!IS_DEVELOPMENT) {
    $user = wp_get_current_user();
    $_SESSION["email"] = $user->user_email;
} else {
    $user = get_userdata(2);
    $_SESSION["email"] = "anyulled@gmail.com";
}

$user_metadata = get_user_meta($user->ID, "", false);
$procedure_instance = new Procedure($_SESSION["email"]);
$procedure_types = $procedure_instance->get_procedure_types();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" media="all" type="text/css"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <title><?= $text["site_name"] . " - " . $text["procedure_title"]; ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="32x32"/>
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="192x192"/>
    <link rel="apple-touch-icon"
          href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"/>
    <link rel="stylesheet" media="all" type="text/css" href="gestoria.css"/>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<?php if (!IS_DEVELOPMENT) {
    get_header();
} ?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4"><?= $text["procedure_title"]; ?></h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="sr-root">
                <div class="sr-main">
                    <form id="payment-form" class="sr-payment-form" method="post">
                        <input id="user_id" type="hidden" name="user_id" value="<?= $user->ID; ?>">
                        <input id="customer" type="hidden" name="customer" value="<?= $user->display_name; ?>"/>
                        <input id="email" type="hidden" name="email" value="<?= $user->user_email; ?>"/>
                        <input id="phone" type="hidden" name="phone"
                               value="<?= $user_metadata["billing_phone"][0]; ?>"/>
                        <input id="city" type="hidden" name="city" value="<?= $user_metadata["billing_city"][0]; ?>"/>
                        <input id="address" type="hidden" name="address"
                               value="<?= $user_metadata["billing_address_1"][0]; ?>"/>
                        <input id="country" type="hidden" name="country"
                               value="<?= $user_metadata["billing_country"][0]; ?>"/>
                        <div class="form-group row">
                            <label for="concept" class="col-4 col-form-label"><?= $text["payment_concept"]; ?></label>
                            <div class="col-8">
                                <select id="concept" name="concept" aria-describedby="conceptHelpBlock"
                                        class="custom-select">
                                    <?php foreach ($procedure_types as $procedure): ?>
                                        <option value="<?= $procedure["id"] ?>"><?= $procedure["name"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="conceptHelpBlock"
                                      class="form-text text-muted"><?= $text["procedure_selection"]; ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-4 col-form-label"><?= $text["input_amount"]; ?></label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input id="amount" name="amount" type="number" class="form-control" value="50">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-euro"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sr-combo-inputs-row">
                            <div class="sr-input sr-card-element" id="card-element"></div>
                        </div>
                        <div class="sr-field-error alert alert-danger d-none" id="card-errors" role="alert"></div>
                        <div class="form-group row">
                            <div class="offset-4 col-8">
                                <button id="submit" class="btn btn-success">
                                    <div class="spinner hidden" id="spinner"></div>
                                    <span id="button-text"><?= $text["pay"]; ?></span><span id="order-amount"></span>
                                </button>
                                <a href="tramites.php" class="btn btn-secondary"><?= $text["back"]; ?></a>
                            </div>
                        </div>
                    </form>
                    <div class="sr-result d-none alert alert-success">
                        <h4><?= $text["process_success"]; ?></h4>
                        <a href="tramites.php" class="btn btn-secondary"><?= $text["back"]; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
<script type="application/javascript">
    let stripe;
    let orderData = {
        amount: document.getElementById("amount").value * 100,
        currency: "eur",
        description: `Trámite: ${$("#concept > option:selected").text()}`,
        items: [{id: "Pago online"}],
        metadata: [{integration_check: "accept_a_payment"}]
    };
    const $form = $("#payment-form");

    document.querySelector("button").disabled = true;
    $(document).on("change", "select, input", () => {
        orderData = {
            ...orderData,
            ...{
                amount: document.getElementById("amount").value * 100,
                description: `Trámite: ${$("#concept > option:selected").text()}`
            }
        };
        $form.off();
        createPaymentIntent();
    });

    const changeLoadingState = isLoading => {
        if (isLoading) {
            document.querySelector("button").disabled = true;
            document.querySelector("#spinner").classList.remove("d-none");
            document.querySelector("#button-text").classList.add("d-none");
        } else {
            document.querySelector("button").disabled = false;
            document.querySelector("#spinner").classList.add("d-none");
            document.querySelector("#button-text").classList.remove("d-none");
        }
    };
    const orderComplete = clientSecret => {
        stripe.retrievePaymentIntent(clientSecret).then(result => {
            const paymentIntent = result.paymentIntent;
            const paymentIntentJson = JSON.stringify(paymentIntent, null, 2);

            $(".sr-payment-form").addClass("d-none");
            const procedureInfo = {
                user_id: $("#user_id").val(),
                procedure_type: $("#concept").val(),
                amount: $("#amount").val(),
                payment_info: paymentIntentJson
            };
            fetch("/gestoria/process-payment.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(procedureInfo)
            })
                .then(response => response.json())
                .then(result => console.log(result))
                .catch(error => console.error(error));

            $(".sr-result").removeClass("d-none");
            setTimeout(() => {
                document.querySelector(".sr-result").classList.add("expand");
            }, 200);

            changeLoadingState(false);
        });
    };
    const defaultIfEmpty = value => value !== "" ? value : "N/A";
    const showError = errorMsgText => {
        changeLoadingState(false);
        $("#card-errors").removeClass("d-none");
        const errorMsg = document.querySelector(".sr-field-error");
        errorMsg.textContent = errorMsgText;
        setTimeout(() => {
            errorMsg.textContent = "";
            $("#card-errors").addClass("d-none");
        }, 4000);
    };
    const pay = (stripe, card, clientSecret) => {
        changeLoadingState(true);

        stripe
            .confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        address: {
                            city: defaultIfEmpty($("#city").val()),
                            line1: defaultIfEmpty($("#address").val()),
                            country: defaultIfEmpty($("#country").val()),
                            state: defaultIfEmpty($("#state").val())
                        },
                        name: $("#customer").val(),
                        email: $("#email").val(),
                        phone: defaultIfEmpty($("#phone").val()),

                    }
                }
            })
            .then(result => {
                if (result.error) {
                    showError(result.error.message);
                } else {
                    orderComplete(clientSecret);
                }
            });
    };
    const setupElements = data => {
        stripe = Stripe(data.publishableKey);
        const elements = stripe.elements();
        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        const card = elements.create("card", {style: style});
        card.mount("#card-element");

        return {
            stripe: stripe,
            card: card,
            clientSecret: data.clientSecret
        };
    };
    const createPaymentIntent = () => {
        fetch("/gestoria/create-payment-intent.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(orderData)
        })
            .then(result => result.json())
            .then(data => setupElements(data))
            .then(({stripe, card, clientSecret}) => {
                document.querySelector("button").disabled = false;
                $form.submit(event => {
                    event.preventDefault();
                    pay(stripe, card, clientSecret);
                });
            })
            .catch(error => console.error(error));
    };

    createPaymentIntent();
</script>
</body>
</html>
