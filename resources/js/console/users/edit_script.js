document.addEventListener("DOMContentLoaded", function () {
    const validateForm = (formSelector, fieldsConfig) => {
        const formElement = document.querySelector(formSelector);
        if (!formElement) return;

        FormValidation.formValidation(formElement, {
            fields: fieldsConfig,
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "",
                    rowSelector: ".form-floating", // Ensures proper positioning of validation messages
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
            init: (instance) => {
                instance.on("plugins.message.placed", function (e) {
                    if (
                        e.element.parentElement.classList.contains(
                            "input-group"
                        )
                    ) {
                        e.element.parentElement.insertAdjacentElement(
                            "afterend",
                            e.messageElement
                        );
                    }
                });

                instance.on("core.element.validated", function (e) {
                    if (e.valid) {
                        e.element.classList.add("is-valid");
                    } else {
                        e.element.classList.remove("is-valid");
                    }
                });

                instance.on("core.form.valid", function () {
                    formElement.submit();
                });
            },
        });
    };

    validateForm("#editUserForm", {
        name: {
            validators: {
                notEmpty: {
                    message: "Please enter the user's name",
                },
                stringLength: {
                    min: 2,
                    message: "Name must be at least 2 characters long",
                },
            },
        },
        email: {
            validators: {
                notEmpty: {
                    message: "Please enter the user's email address",
                },
                emailAddress: {
                    message: "Please enter a valid email address",
                },
            },
        },
        role_id: {
            validators: {
                notEmpty: {
                    message: "Please select a role",
                },
            },
        },
        password: {
            validators: {
                stringLength: {
                    min: 8,
                    message: "Password must be at least 8 characters long",
                },
            },
        },
        password_confirmation: {
            validators: {
                callback: {
                    message: "Please confirm the password",
                    callback: function (value, validator, $field) {
                        const passwordValue = document.querySelector(
                            '#editUserForm [name="password"]'
                        ).value;
                        if (passwordValue === "") {
                            return true; // If password is empty, confirmation is not required
                        }
                        return value.length > 0; // If password is not empty, confirmation is required
                    },
                },
                identical: {
                    compare: function () {
                        return document.querySelector(
                            '#editUserForm [name="password"]'
                        ).value;
                    },
                    message: "The password and its confirmation do not match",
                },
            },
        },
    });
});
