document.addEventListener("DOMContentLoaded", () => {

    loadNurserySettings();

});


async function loadNurserySettings() {

    try {

        const response = await fetch(
            BASE_URL + "api/get_settings.php"
        );


        const result = await response.json();


        if (!response.ok || !result.success) {

            throw new Error(
                result.message ||
                "Unable to load nursery settings"
            );

        }


        const settings = result.settings;


        /*
        |--------------------------------------------------------------------------
        | Nursery Name
        |--------------------------------------------------------------------------
        */

        const nurseryName =
            document.getElementById("nurseryName");

        if (nurseryName) {

            nurseryName.textContent =
                settings.nursery_name || "GreenLeaf Nursery";
        }


        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        const nurseryAddress =
            document.getElementById("nurseryAddress");

        if (nurseryAddress) {

            nurseryAddress.textContent =
                settings.address || "";
        }


        /*
        |--------------------------------------------------------------------------
        | Phone
        |--------------------------------------------------------------------------
        */

        const nurseryPhone =
            document.getElementById("nurseryPhone");

        if (nurseryPhone && settings.phone) {

            nurseryPhone.textContent =
                settings.phone;

            nurseryPhone.href =
                "tel:+" + settings.phone;
        }


        /*
        |--------------------------------------------------------------------------
        | Facebook
        |--------------------------------------------------------------------------
        */

        const facebookLink =
            document.getElementById("facebookLink");

        if (facebookLink && settings.facebook_url) {

            facebookLink.href =
                settings.facebook_url;

            facebookLink.style.display =
                "inline-flex";
        }


        /*
        |--------------------------------------------------------------------------
        | WhatsApp
        |--------------------------------------------------------------------------
        */

        const whatsappLink =
            document.getElementById("whatsappLink");

        if (whatsappLink && settings.whatsapp_url) {

            whatsappLink.href =
                settings.whatsapp_url;

            whatsappLink.style.display =
                "inline-flex";
        }


    } catch (error) {

        console.error(
            "Nursery settings error:",
            error
        );

    }

}