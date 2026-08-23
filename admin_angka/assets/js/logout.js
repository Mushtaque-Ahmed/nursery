document
    .querySelectorAll(".logoutButton")
    .forEach(button => {

        button.addEventListener("click", async () => {

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

                } else {

                    alert(
                        result.message ||
                        "Logout failed. Please try again."
                    );

                }

            } catch (error) {

                console.error(
                    "Logout error:",
                    error
                );

                alert(
                    "Something went wrong while logging out."
                );

            }

        });

    });