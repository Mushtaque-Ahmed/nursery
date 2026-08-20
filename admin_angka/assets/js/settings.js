document.addEventListener("DOMContentLoaded", () => {

    const saveButton =
        document.getElementById("saveNurserySettings");


    /*
    |--------------------------------------------------------------------------
    | Load Previous Settings
    |--------------------------------------------------------------------------
    */

    loadNurserySettings();


    /*
    |--------------------------------------------------------------------------
    | Check Save Button
    |--------------------------------------------------------------------------
    */

    if (!saveButton) {
        return;
    }


    saveButton.addEventListener("click", async () => {

        const nurseryName =
            document
                .getElementById("nursery_name")
                .value
                .trim();

        const address =
            document
                .getElementById("nursery_address")
                .value
                .trim();

        const phone =
            document
                .getElementById("phone")
                .value
                .trim();

        const facebookUrl =
            document
                .getElementById("facebook_url")
                .value
                .trim();

        const whatsappUrl =
            document
                .getElementById("whatsapp_url")
                .value
                .trim();


        /*
        |--------------------------------------------------------------------------
        | Nursery Name Validation
        |--------------------------------------------------------------------------
        */

        if (!nurseryName) {

            alert("Please enter nursery name");

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Mobile Number Validation
        |--------------------------------------------------------------------------
        */

        if (phone !== "") {

            const phoneRegex =
                /^91[6-9][0-9]{9}$/;


            if (!phoneRegex.test(phone)) {

                alert(
                    "Please enter a valid mobile number with 91 country code.\n\n" +
                    "Example: 919876543210"
                );

                document
                    .getElementById("phone")
                    .focus();

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Prepare Data
        |--------------------------------------------------------------------------
        */

        const data = {

            nursery_name: nurseryName,

            address: address,

            phone: phone,

            facebook_url: facebookUrl,

            whatsapp_url: whatsappUrl

        };


        saveButton.disabled = true;

        saveButton.textContent =
            "Saving...";


        try {

            const response =
                await fetch(
                    "api/settings.php",
                    {
                        method: "POST",

                        headers: {
                            "Content-Type":
                                "application/json"
                        },

                        body:
                            JSON.stringify(data)
                    }
                );


            const result =
                await response.json();


            if (
                !response.ok ||
                !result.success
            ) {

                throw new Error(
                    result.message ||
                    "Failed to save settings"
                );

            }


            alert(
                result.message ||
                "Settings saved successfully"
            );


        } catch (error) {

            console.error(
                "Settings error:",
                error
            );


            alert(
                error.message ||
                "Something went wrong while saving settings"
            );


        } finally {

            saveButton.disabled = false;

            saveButton.textContent =
                "Save Settings";
        }

    });

});


/*
|--------------------------------------------------------------------------
| Load Nursery Settings
|--------------------------------------------------------------------------
*/

async function loadNurserySettings() {

    try {

        const response =
            await fetch(
                "api/get_settings.php",
                {
                    method: "GET",

                    cache: "no-store"
                }
            );


        const result =
            await response.json();


        if (
            !response.ok ||
            !result.success
        ) {

            throw new Error(
                result.message ||
                "Unable to load nursery settings"
            );

        }


        const settings =
            result.settings;


        /*
        |--------------------------------------------------------------------------
        | Nursery Name
        |--------------------------------------------------------------------------
        */

        const nurseryName =
            document.getElementById(
                "nursery_name"
            );

        if (nurseryName) {

            nurseryName.value =
                settings.nursery_name || "";
        }


        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        const address =
            document.getElementById(
                "nursery_address"
            );

        if (address) {

            address.value =
                settings.address || "";
        }


        /*
        |--------------------------------------------------------------------------
        | Phone
        |--------------------------------------------------------------------------
        */

        const phone =
            document.getElementById(
                "phone"
            );

        if (phone) {

            phone.value =
                settings.phone || "";
        }


        /*
        |--------------------------------------------------------------------------
        | Facebook
        |--------------------------------------------------------------------------
        */

        const facebookUrl =
            document.getElementById(
                "facebook_url"
            );

        if (facebookUrl) {

            facebookUrl.value =
                settings.facebook_url || "";
        }


        /*
        |--------------------------------------------------------------------------
        | WhatsApp
        |--------------------------------------------------------------------------
        */

        const whatsappUrl =
            document.getElementById(
                "whatsapp_url"
            );

        if (whatsappUrl) {

            whatsappUrl.value =
                settings.whatsapp_url || "";
        }


    } catch (error) {

        console.error(
            "Load settings error:",
            error
        );

    }

}