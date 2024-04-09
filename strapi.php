<?php /* Template Name: Strapi*/ ?>
<?php get_header() ?>

<script src="https://js.stripe.com/v3/"></script>
<style>

    p {
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    letter-spacing: -0.154px;
    color: #242d60;
    width: 100%;
    padding: 0 20px;
    box-sizing: border-box;
    }
    img {
    border-radius: 6px;
    margin: 10px;
    width: 54px;
    height: 57px;
    }
    h3,
    h5 {
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    letter-spacing: -0.154px;
    color: #242d60;
    margin: 0;
    }
    h5 {
    opacity: 0.5;
    }
    button {
    height: 36px;
    background: #556cd6;
    color: white;
    width: 100%;
    font-size: 14px;
    border: 0;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 0.6;
    border-radius: 0 0 6px 6px;
    transition: all 0.2s ease;
    box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
    }
    button:hover {
    opacity: 0.8;
    }

    :root {
    --light-grey: #F6F9FC;
    --dark-terminal-color: #0A2540;
    --accent-color: #635BFF;
    --radius: 3px;
    }


    form > * {
    margin: 10px 0;
    }

    button {
    background-color: var(--accent-color);
    }

    button {
    background: var(--accent-color);
    border-radius: var(--radius);
    color: white;
    border: 0;
    padding: 12px 16px;
    margin-top: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: block;
    }
    button:hover {
    filter: contrast(115%);
    }
    button:active {
    transform: translateY(0px) scale(0.98);
    filter: brightness(0.9);
    }
    button:disabled {
    opacity: 0.5;
    cursor: none;
    }
    .ElementsApp input,
    input, select {
    display: block!important;
    font-size:18px;
    width: 100%!important;
    margin-bottom: 10px;
    }

    label {
    display: block;
    }

    a {
    color: var(--accent-color);
    font-weight: 900;
    }

    small {
    font-size: .6em;
    }

    fieldset, input, select {
    border: 1px solid #efefef;
    }

    #payment-form {
    border: #F6F9FC solid 1px;
    border-radius: var(--radius);
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 30px 50px -20px rgb(50 50 93 / 25%), 0 30px 60px -30px rgb(0 0 0 / 30%);
    }

    #messages {
    font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New';
    display: none; /* hide initially, then show once the first message arrives */
    background-color: #0A253C;
    color: #00D924;
    padding: 20px;
    margin: 20px 0;
    border-radius: var(--radius);
    font-size:0.7em;
    overflow: scroll;
    }
</style>
<main>
      <a href="/">home</a>
      <h1>Card</h1>

      <p>
        <h4>Try a <a href="https://stripe.com/docs/testing#cards" target="_blank">test card</a>:</h4>
        <div>
          <code>4242424242424242</code> (Visa)
        </div>
        <div>
          <code>5555555555554444</code> (Mastercard)
        </div>
        <div>
          <code>4000002500003155</code> (Requires <a href="https://www.youtube.com/watch?v=2kc-FjU2-mY" target="_blank">3DSecure</a>)
        </div>
      </p>

      <p>
        Use any future expiration, any 3 digit CVC, and any postal code.
      </p>

      <form id="payment-form">
        <label for="name">
          Name
        </label>
        <input id="name" placeholder="Jenny Rosen" value="Jenny Rosen" required />

        <label for="card-element">
          Card
        </label>
        <div id="card-element">
          <!-- Elements will create input elements here -->
        </div>
        <!-- We'll put the error messages in this element -->
        <div id="card-errors" role="alert"></div>

        <button id="submit">Pay</button>
      </form>

      <div id="messages" role="alert" style="display: none;"></div>
    </main>
    <script>
      // Helper for displaying status messages.
      const addMessage = (message) => {
        const messagesDiv = document.querySelector('#messages');
        messagesDiv.style.display = 'block';
        const messageWithLinks = addDashboardLinks(message);
        messagesDiv.innerHTML += `> ${messageWithLinks}<br>`;
        console.log(`Debug: ${message}`);
      };

      // Adds links for known Stripe objects to the Stripe dashboard.
      const addDashboardLinks = (message) => {
      const piDashboardBase = 'https://dashboard.stripe.com/test/payments';
      return message.replace(
        /(pi_(\S*)\b)/g,
        `<a href="${piDashboardBase}/$1" target="_blank">$1</a>`
      );
    };

      document.addEventListener('DOMContentLoaded', async () => {
      // Load the publishable key from the server. The publishable key
      // is set in your .env file.
      const publishableKey = 'pk_test_51P2NR8P4kMLp6ioTL4ke8gLMHrYe2cCzFU36LF7FQfOHrfmtXS321AECNvMyWPLIgaeFQmhZOsROC0I6xwceKUss00b0B7bVbn'
      if (!publishableKey) {
        addMessage(
          'No publishable key returned from the server. Please check `.env` and try again'
        );
        alert('Please set your Stripe publishable API key in the .env file');
      }

        const stripe = Stripe(publishableKey, {
          apiVersion: '2020-08-27',
        });

      const elements = stripe.elements();
      const card = elements.create('card');
      card.mount('#card-element');

        // When the form is submitted...
        const form = document.getElementById('payment-form');
        let submitted = false;
        form.addEventListener('submit', async (e) => {
          e.preventDefault();

          // Disable double submission of the form
          if(submitted) { return; }
          submitted = true;
          form.querySelector('button').disabled = true;

          // Make a call to the server to create a new
          // payment intent and store its client_secret. client_secret

          const {error: backendError, client_secret} = await fetch(
            'https://miamigreens.com/wp-json/create/payment-intent',
            {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                currency: 'usd'}),
            }
          ).then((r) => r.json());

          if (backendError) {
            addMessage(backendError.message);

            // reenable the form.
            submitted = false;
            form.querySelector('button').disabled = false;
            return;
          }

          addMessage(`Client secret returned.`);

          const nameInput = document.querySelector('#name');

          // Confirm the card payment given the client_secret
          // from the payment intent that was just created on
          // the server.
          const {error: stripeError, paymentIntent} = await stripe.confirmCardPayment(
            client_secret,
            {
              payment_method: {
                card: card,
                billing_details: {
                  name: nameInput.value,
                },
              },
            }
          );

          if (stripeError) {
            addMessage(stripeError.message);

            // reenable the form.
            submitted = false;
            form.querySelector('button').disabled = false;
            return;
          }

          addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
        });
      });
    </script>
<?php get_footer() ?>


