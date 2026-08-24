      <!-- footer start -->
      <style>
          /* Footer Links Smooth Transition */
          .footer-menu-item a {
              display: inline-block;
              transition: all 0.3s ease;
          }

          .footer-menu-item a:hover {
              color: #fca311 !important;
              transform: translateX(5px);
          }

          /* Social Icons Bouncy Hover */
          .footer-social-item {
              transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
          }

          .footer-social-item:hover {
              transform: scale(1.2) translateY(-5px);
          }
      </style>
      <footer class="overflow-hidden footer-style-2">
          <div class="footer-top bg-5">
              <div class="container">
                  <div class="footer-widget-wrapper">
                      <div class="row justify-content-between">
                          <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                              <div class="footer-widget-inner">
                                  <h4 class="footer-heading d-flex align-items-center justify-content-between">
                                      <span>About</span>
                                      <span class="d-md-none">
                                          <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                              width="24" height="24" viewBox="0 0 24 24" fill="none"
                                              stroke="#ffffff" stroke-width="1" stroke-linecap="round"
                                              stroke-linejoin="round">
                                              <polyline points="6 9 12 15 18 9"></polyline>
                                          </svg>
                                      </span>
                                  </h4>
                                  <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                      <li class="footer-menu-item">
                                          <a href="http://portal.learningalliance.edu.pk">Student Portal</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="http://www.learningalliance.edu.pk/web/policy-manual/">Policy
                                              Manual</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="#">Societies</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="">Alumni</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="#">Newsletter</a>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                              <div class="footer-widget-inner">
                                  <h4 class="footer-heading d-flex align-items-center justify-content-between">
                                      <span>Help</span>
                                      <span class="d-md-none">
                                          <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                              width="24" height="24" viewBox="0 0 24 24" fill="none"
                                              stroke="#ffffff" stroke-width="1" stroke-linecap="round"
                                              stroke-linejoin="round">
                                              <polyline points="6 9 12 15 18 9"></polyline>
                                          </svg>
                                      </span>
                                  </h4>
                                  <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                      <li class="footer-menu-item">
                                          <a href="{{ route('contactus') }}">Contact Us</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="http://www.learningalliance.edu.pk/web/career">Careers</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="">Transport</a>
                                      </li>
                                      <li class="footer-menu-item">
                                          <a href="#">OSLA</a>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="col-xl-4 col-lg-5 col-md-6 col-12 footer-widget">
                              <div class="footer-widget-inner">
                                  <h4 class="footer-logo">
                                      <a href="{{ route('home') }}">
                                          <img src="{{ asset('index') }}/assets/img/logo-right.png"
                                              alt="Learning Alliance" style="max-width: 250px; max-height: 80px;" />
                                      </a>
                                  </h4>
                                  <div class="footer-widget-inner">
                                      <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                          <li class="footer-menu-item"><a
                                                  href="mailto:info@learningalliance.edu.pk"><b>Email:</b>
                                                  info@learningalliance.edu.pk</a>
                                          </li>
                                          <li class="footer-menu-item"><a href="tel:+9242111666633"><b>DHA:</b>
                                                  +92-42-111-66-66-33</a>
                                          </li>
                                          <li class="footer-menu-item"><a href="tel:+9242111666633"><b>Gulberg:</b>
                                                  +92-42-357-56-41-0</a>
                                          </li>
                                          <li class="footer-menu-item"><a href="tel:+9242111666611"><b>Aziz Avenue:</b>
                                                  +92-42-111-66-66-11</a></li>
                                          <li class="footer-menu-item"> <a href="tel:+9241111666633"><b>Faisalabad:</b>
                                                  +92-41-111-66-66-33</a></li>
                                      </ul>
                                  </div>
                                  <div class="footer-social-wrapper text-center">
                                      <ul class="footer-social list-unstyled d-flex align-items-center flex-wrap mb-0">
                                          <li class="footer-social-item">
                                              <a href="https://www.facebook.com/LearningAlliance.LahorePreschool">
                                                  <svg class="icon icon-facebook" width="20" height="20"
                                                      viewBox="0 0 20 20" fill="none"
                                                      xmlns="http://www.w3.org/2000/svg">
                                                      <path
                                                          d="M16.3021 4.58314H14.0258C13.1546 4.58314 12.4098 4.89227 11.7916 5.51054C11.2014 6.12881 10.9063 6.87354 10.9063 7.74473V9.97892H9.09368V12.6768H10.9063V18.9578H13.6042V12.6768H16.3021V9.97892H13.6042V8.16628C13.6042 7.94145 13.6885 7.74473 13.8571 7.57611C14.0258 7.37939 14.2365 7.28103 14.4895 7.28103H16.3021V4.58314ZM1 2C1 1.44772 1.44772 1 2 1H18C18.5523 1 19 1.44772 19 2V17.9578C19 18.5101 18.5523 18.9578 18 18.9578H2C1.44772 18.9578 1 18.5101 1 17.9578V2Z"
                                                          fill="#FEFEFE"></path>
                                                  </svg>
                                              </a>
                                          </li>
                                          <li class="footer-social-item">
                                              <a href="https://www.instagram.com/Learning_Alliance/">
                                                  <svg class="icon icon-instagram" width="20" height="20"
                                                      viewBox="0 0 20 20" fill="none"
                                                      xmlns="http://www.w3.org/2000/svg">
                                                      <path
                                                          d="M9.99998 2.62165C12.4031 2.62165 12.6877 2.6308 13.6367 2.6741C14.5142 2.71415 14.9908 2.86077 15.3079 2.98398C15.728 3.14725 16.0278 3.34231 16.3428 3.65723C16.6577 3.97215 16.8528 4.272 17.016 4.69206C17.1392 5.00923 17.2859 5.48577 17.3259 6.36323C17.3692 7.31228 17.3783 7.5969 17.3783 10C17.3783 12.4031 17.3692 12.6878 17.3259 13.6368C17.2859 14.5143 17.1392 14.9908 17.016 15.308C16.8528 15.728 16.6577 16.0279 16.3428 16.3428C16.0278 16.6577 15.728 16.8528 15.3079 17.016C14.9908 17.1393 14.5142 17.2859 13.6367 17.3259C12.6879 17.3692 12.4032 17.3784 9.99998 17.3784C7.59672 17.3784 7.3121 17.3692 6.36323 17.3259C5.48574 17.2859 5.00919 17.1393 4.69206 17.016C4.27196 16.8528 3.97212 16.6577 3.6572 16.3428C3.34227 16.0279 3.14721 15.728 2.98398 15.308C2.86073 14.9908 2.71411 14.5143 2.67406 13.6368C2.63076 12.6878 2.62162 12.4031 2.62162 10C2.62162 7.5969 2.63076 7.31228 2.67406 6.36326C2.71411 5.48577 2.86073 5.00923 2.98398 4.69206C3.14721 4.272 3.34227 3.97215 3.6572 3.65723C3.97212 3.34231 4.27196 3.14725 4.69206 2.98398C5.00919 2.86077 5.48574 2.71415 6.36319 2.6741C7.31224 2.6308 7.59687 2.62165 9.99998 2.62165ZM9.99998 1C7.55571 1 7.24926 1.01036 6.28931 1.05416C5.33133 1.09789 4.67712 1.25001 4.10462 1.47251C3.51279 1.70251 3.01088 2.01025 2.51055 2.51058C2.01021 3.01092 1.70247 3.51283 1.47247 4.10466C1.24997 4.67716 1.09785 5.33137 1.05412 6.28935C1.01032 7.24926 1 7.55575 1 10C1 12.4443 1.01032 12.7508 1.05412 13.7107C1.09785 14.6687 1.24997 15.3229 1.47247 15.8954C1.70247 16.4872 2.01021 16.9891 2.51055 17.4895C3.01088 17.9898 3.51279 18.2975 4.10462 18.5275C4.67712 18.75 5.33133 18.9021 6.28931 18.9459C7.24926 18.9897 7.55571 19 9.99998 19C12.4443 19 12.7507 18.9897 13.7107 18.9459C14.6686 18.9021 15.3228 18.75 15.8953 18.5275C16.4872 18.2975 16.9891 17.9898 17.4894 17.4895C17.9898 16.9891 18.2975 16.4872 18.5275 15.8954C18.75 15.3229 18.9021 14.6687 18.9458 13.7107C18.9896 12.7508 19 12.4443 19 10C19 7.55575 18.9896 7.24926 18.9458 6.28935C18.9021 5.33137 18.75 4.67716 18.5275 4.10466C18.2975 3.51283 17.9898 3.01092 17.4894 2.51058C16.9891 2.01025 16.4872 1.70251 15.8953 1.47251C15.3228 1.25001 14.6686 1.09789 13.7107 1.05416C12.7507 1.01036 12.4443 1 9.99998 1ZM9.99998 5.37838C7.44753 5.37838 5.37835 7.44757 5.37835 10C5.37835 12.5525 7.44753 14.6217 9.99998 14.6217C12.5524 14.6217 14.6216 12.5525 14.6216 10C14.6216 7.44757 12.5524 5.37838 9.99998 5.37838ZM9.99998 13C8.34314 13 6.99996 11.6569 6.99996 10C6.99996 8.34317 8.34314 7 9.99998 7C11.6568 7 13 8.34317 13 10C13 11.6569 11.6568 13 9.99998 13ZM15.8842 5.19579C15.8842 5.79226 15.4007 6.27581 14.8042 6.27581C14.2077 6.27581 13.7242 5.79226 13.7242 5.19579C13.7242 4.59931 14.2077 4.1158 14.8042 4.1158C15.4007 4.1158 15.8842 4.59931 15.8842 5.19579Z"
                                                          fill="#FEFEFE"></path>
                                                  </svg>
                                              </a>
                                          </li>
                                          <li class="footer-social-item">
                                              <a href="http://portal.learningalliance.edu.pk/" target="_blank"
                                                  rel="noopener noreferrer">
                                                  <svg class="icon icon-portal" width="20" height="20"
                                                      viewBox="0 0 24 24" fill="none"
                                                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                      <!-- Arrow leaving a square (external/portal) -->
                                                      <path fill="#FEFEFE"
                                                          d="M14 3h7v7h-2V6.41l-8.3 8.3-1.4-1.42 8.3-8.3H14V3z" />
                                                      <path fill="#FEFEFE"
                                                          d="M5 5h6V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6h-2v6H5V5z" />
                                                  </svg>
                                              </a>
                                          </li>

                                          <li class="footer-social-item">
                                              <a href="https://www.youtube.com/channel/UCglZev_w-vaquQJkVuLFcug">
                                                  <svg class="icon icon-youtube" width="20" height="20"
                                                      viewBox="0 0 20 20" fill="none"
                                                      xmlns="http://www.w3.org/2000/svg">
                                                      <path
                                                          d="M18.7892 6.69789C18.9297 7.6815 19 8.65105 19 9.60656V10.9555L18.7892 13.8642C18.6768 14.6792 18.4379 15.2693 18.0726 15.6347C17.6792 16.0281 17.089 16.281 16.3021 16.3934C15.5433 16.4496 14.63 16.4918 13.5621 16.5199C12.5222 16.548 11.6651 16.5621 10.9906 16.5621H9.97892C6.85948 16.534 4.82201 16.4778 3.86651 16.3934C3.86651 16.3934 3.7541 16.3794 3.52927 16.3513C3.30445 16.3232 3.12178 16.2951 2.98126 16.267C2.84075 16.2389 2.65808 16.1686 2.43326 16.0562C2.23653 15.9438 2.05386 15.8033 1.88525 15.6347C1.74473 15.466 1.60422 15.2412 1.4637 14.9602C1.35129 14.6511 1.28103 14.3841 1.25293 14.1593L1.16862 13.8642C1.05621 12.8806 1 11.911 1 10.9555V9.60656L1.16862 6.69789C1.28103 5.8829 1.51991 5.29274 1.88525 4.9274C2.27869 4.50585 2.8829 4.25293 3.69789 4.16862C4.45667 4.11241 5.35597 4.07026 6.39578 4.04215C7.4356 4.01405 8.29274 4 8.96721 4H9.97892C12.5082 4 14.6159 4.05621 16.3021 4.16862C17.089 4.25293 17.6792 4.50585 18.0726 4.9274C18.185 5.03981 18.2834 5.18033 18.3677 5.34895C18.452 5.48946 18.5222 5.64403 18.5785 5.81265C18.6347 5.95316 18.6768 6.09368 18.7049 6.23419C18.733 6.37471 18.7611 6.48712 18.7892 6.57143V6.69789ZM12.4239 10.4075L13.0141 10.1124L8.16628 7.58314V12.6417L12.4239 10.4075Z"
                                                          fill="#FEFEFE"></path>
                                                  </svg>
                                              </a>
                                          </li>
                                          <!-- Phone -->
                                          <li class="footer-social-item">
                                              <a href="tel:+9242111666633">
                                                  <svg class="icon icon-phone" width="20" height="20"
                                                      viewBox="0 0 24 24" fill="none"
                                                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                      <path fill="#FEFEFE"
                                                          d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25c1.24.4 2.47.6 3.6.6a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 7a1 1 0 0 1 1-1h3.4a1 1 0 0 1 1 1c0 1.23.21 2.46.6 3.6a1 1 0 0 1-.4 1.2z" />
                                                  </svg>
                                              </a>
                                          </li>

                                          <!-- Email -->
                                          <li class="footer-social-item">
                                              <a href="mailto:info@learningalliance.edu.pk">
                                                  <svg class="icon icon-email" width="20" height="20"
                                                      viewBox="0 0 24 24" fill="none"
                                                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                      <path fill="#FEFEFE"
                                                          d="M20 4H4a2 2 0 0 0-2 2v.4l10 6.25L22 6.4V6a2 2 0 0 0-2-2zm0 4.2-8 5-8-5V18a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.2z" />
                                                  </svg>
                                              </a>
                                          </li>

                                      </ul>
                                  </div>

                              </div>
                          </div>
                      </div>
                  </div>
              </div>

          </div>
      </footer>
      <!-- footer end -->
