  @include('user.user-dashboard-base')

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <div class="content-wrapper py-5">
      <style>
          .upload-box {
              border: 2px dashed #3498db;
              padding: 30px;
              text-align: center;
              border-radius: 15px;
              cursor: pointer;
              color: #666;
              transition: all 0.3s ease;
              background-color: #f8f9fa;
          }
          .upload-box:hover {
              background-color: #e8f4f8;
              border-color: #2980b9;
              transform: translateY(-2px);
              box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          }
          .form-section {
              background-color: #ffffff;
              padding: 25px;
              border-radius: 15px;
              box-shadow: 0 5px 20px rgba(0,0,0,0.08);
              margin-bottom: 20px;
              transition: all 0.3s ease;
          }
          .form-section:hover {
              transform: translateY(-3px);
              box-shadow: 0 8px 25px rgba(0,0,0,0.1);
          }
          .form-control, .form-select {
              border-radius: 8px;
              border: 1px solid #e0e0e0;
              padding: 10px 15px;
              transition: all 0.3s;
          }
          .form-control:focus, .form-select:focus {
              border-color: #3498db;
              box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
          }
          h5 {
              color: #2c3e50;
              font-weight: 600;
              margin-bottom: 20px;
              padding-bottom: 10px;
              border-bottom: 2px solid #3498db;
          }
          .form-label {
              color: #34495e;
              font-weight: 500;
          }
          .btn-primary {
              background-color: #3498db;
              border: none;
              padding: 10px 25px;
              border-radius: 8px;
              transition: all 0.3s;
          }
          .btn-primary:hover {
              background-color: #2980b9;
              transform: translateY(-2px);
          }
          .fa-camera {
              color: #3498db;
              margin-bottom: 15px;
          }
      </style>
  </head>
  <div>
  <div class="mx-3">
      <div class="row">
          <!-- Upload Contact Photo -->
          <div class="col-md-4 mb-3">
              <div class="upload-box">
                  <i class="fa fa-camera fa-3x mb-3"></i>
                  <h5 class="mb-3">Upload Contact Photo</h5>
                  <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">Upload Photo</button>
                  <input type="file" id="fileInput" style="display: none" accept="image/jpeg,image/png,image/gif">                  <p class="text-muted">Click to upload your photo</p>
                  <small class="text-primary">Supported formats: JPG, PNG, GIF</small>
              </div>
          </div>
          <!-- Contact Info -->
          <div class="col-md-8">
              <div class="form-section">
                  <h5>Contact Information</h5>
                  <form>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label for="firstName" class="form-label">First Name*</label>
                                  <input type="text" class="form-control" id="firstName" placeholder="Enter your first name">
                              </div>
                              <div class="mb-3">
                                  <label for="lastName" class="form-label">Last Name</label>
                                  <input type="text" class="form-control" id="lastName" placeholder="Enter your last name">
                              </div>
                              <div class="mb-3">
                                  <label for="companyName" class="form-label">Company Name</label>
                                  <input type="text" class="form-control" id="companyName" placeholder="Enter company name">
                              </div>
                              <div class="mb-3">
                                  <label for="jobTitle" class="form-label">Job Title</label>
                                  <input type="text" class="form-control" id="jobTitle" placeholder="Enter your job title">
                              </div>
                              <div class="mb-3">
                                  <label for="dob" class="form-label">Date of Birth</label>
                                  <input type="date" class="form-control" id="dob">
                              </div>
                              <div class="row">
                                  <div class="col-md-4">
                                      <label for="countryCode" class="form-label">Country Code</label>
                                      <input type="text" class="form-control" id="countryCode" placeholder="+1">
                                  </div>
                                  <div class="col-md-8">
                                      <label for="phoneNumber" class="form-label">Phone Number</label>
                                      <input type="tel" class="form-control" id="phoneNumber" placeholder="Enter phone number">
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label for="emailAddress" class="form-label">OMAIL Address</label>
                                  <input type="email" class="form-control" id="emailAddress" placeholder="Enter your OMAIL address">
                              </div>
                              <div class="mb-3">
                                  <label for="altEmail" class="form-label">Alternative Email*</label>
                                  <input type="email" class="form-control" id="altEmail" placeholder="Enter alternative email">
                              </div>
                              <div class="mb-3">
                                  <label for="address" class="form-label">Address</label>
                                  <textarea class="form-control" id="address" rows="3" placeholder="Enter your full address"></textarea>
                              </div>
                              <div class="mb-3">
                                  <label for="country" class="form-label">Country*</label>
                                  <select class="form-select" id="country">
                                      <option selected disabled>Select your country</option>
                                      <option>Rwanda</option>
                                      <option>USA</option>
                                      <option>Canada</option>
                                  </select>
                              </div>
                              <div class="row">
                                  <div class="col-md-6">
                                      <label for="state" class="form-label">Select State</label>
                                      <select class="form-select" id="state">
                                          <option selected disabled>Select state</option>
                                      </select>
                                  </div>
                                  <div class="col-md-6">
                                      <label for="zipcode" class="form-label">Zipcode</label>
                                      <input type="text" class="form-control" id="zipcode" placeholder="Enter zipcode">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  </div>