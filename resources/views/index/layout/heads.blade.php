  <head>
      <title>Learning Alliance</title>
      <!-- meta tags -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <meta name="description" content="meta description" />
      <link rel="shortcut icon" href="{{ asset('index') }}/assets/img/logo-right.png" type="image/x-icon" />
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

      <!-- all css -->
      <style>
          :root {
              --primary-color: #00234d;
              --secondary-color: #f76b6a;
              --btn-primary-border-radius: 0.25rem;
              --btn-primary-color: #fff;
              --btn-primary-background-color: #00234d;
              --btn-primary-border-color: #00234d;
              --btn-primary-hover-color: #fff;
              --btn-primary-background-hover-color: #00234d;
              --btn-primary-border-hover-color: #00234d;
              --btn-primary-font-weight: 500;
              --btn-secondary-border-radius: 0.25rem;
              --btn-secondary-color: #00234d;
              --btn-secondary-background-color: transparent;
              --btn-secondary-border-color: #00234d;
              --btn-secondary-hover-color: #fff;
              --btn-secondary-background-hover-color: #00234d;
              --btn-secondary-border-hover-color: #00234d;
              --btn-secondary-font-weight: 500;
              --heading-color: #000;
              --heading-font-family: "Poppins", sans-serif;
              --heading-font-weight: 700;
              --title-color: #000;
              --title-font-family: "Poppins", sans-serif;
              --title-font-weight: 400;
              --body-color: #000;
              --body-background-color: #fff;
              --body-font-family: "Poppins", sans-serif;
              --body-font-size: 14px;
              --body-font-weight: 400;
              --section-heading-color: #000;
              --section-heading-font-family: "Poppins", sans-serif;
              --section-heading-font-size: 48px;
              --section-heading-font-weight: 600;
              --section-subheading-color: #000;
              --section-subheading-font-family: "Poppins", sans-serif;
              --section-subheading-font-size: 16px;
              --section-subheading-font-weight: 400;
          }
      </style>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

      <link rel="stylesheet" href="{{ asset('index') }}/assets/css/vendor.css" />
      <link rel="stylesheet" href="{{ asset('index') }}/assets/css/style.css" />
      <style>
          /* Toastr: force solid colors (no gradient) + white text */
          #toast-container>.toast {
              background-image: none !important;
              color: #fff !important;
          }

          #toast-container>.toast-success {
              background-color: #51A351 !important;
          }

          #toast-container>.toast-error {
              background-color: #BD362F !important;
          }

          #toast-container>.toast-info {
              background-color: #2F96B4 !important;
          }

          #toast-container>.toast-warning {
              background-color: #F89406 !important;
          }

          /* Optional: z-index if hidden beneath header */
          #toast-container {
              z-index: 10999 !important;
          }
      </style>
  </head>
