document
    .getElementById("logoutButton")
    .addEventListener("click", async () => {

        try {

            const response = await fetch(
                "api/admin_logout.php",
                {
                    method: "POST"
                }
            );

            const result = await response.json();

            if (result.success) {

                window.location.href =
                    "login.html";
            }

        } catch (error) {

            console.error(
                "Logout error:",
                error
            );

        }

    });