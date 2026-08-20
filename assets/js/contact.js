document.addEventListener("DOMContentLoaded", () => {

    loadContactSettings();

});


async function loadContactSettings() {

    try {

        const response = await fetch(
            BASE_URL + "api/get_settings.php"
        );


        const result =
            await response.json();


        if (!response.ok || !result.success) {

            throw new Error(
                result.message ||
                "Unable to load contact information"
            );

        }


        const settings =
            result.settings;


        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        const address =
            document.getElementById("contactAddress");

        if (address) {

            address.textContent =
                settings.address ||
                "Address not available";
        }


        /*
        |--------------------------------------------------------------------------
        | Phone
        |--------------------------------------------------------------------------
        */

        const phone =
            document.getElementById("contactPhone");

        if (phone && settings.phone) {

            phone.textContent =
                "+" + settings.phone;

            phone.href =
                "tel:+" + settings.phone;
        }


        /*
        |--------------------------------------------------------------------------
        | WhatsApp
        |--------------------------------------------------------------------------
        */

        const whatsapp =
            document.getElementById("contactWhatsapp");

        if (
            whatsapp &&
            settings.whatsapp_url
        ) {

            whatsapp.href =
                settings.whatsapp_url;

            whatsapp.style.display =
                "inline-flex";
        }


        /*
        |--------------------------------------------------------------------------
        | Facebook
        |--------------------------------------------------------------------------
        */

        const facebook =
            document.getElementById("contactFacebook");

        if (
            facebook &&
            settings.facebook_url
        ) {

            facebook.href =
                settings.facebook_url;

            facebook.style.display =
                "flex";
        }


    } catch (error) {

        console.error(
            "Contact settings error:",
            error
        );

    }

}