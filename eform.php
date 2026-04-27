<?php
require_once "db_connection.php";

$db = new Database();
$courses = $db->getCourses();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Enrollment Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      display: flex;
      justify-content: center;
      padding: 20px;
    }

    form {
      background: #fff;
      padding: 20px 25px;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 600px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff;
    }

    .row {
      display: flex;
      gap: 15px;
      margin-bottom: 12px;
      flex-wrap: wrap;
    }

    .row label {
      font-weight: bold;
      font-size: 14px;
      margin-bottom: 3px;
    }

    .row input,
    .row select {
      flex: 1;
      padding: 6px 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    input[readonly] {
      background-color: #e9ecef;
      cursor: not-allowed;
    }

    .section {
      margin-top: 20px;
      padding-top: 10px;
      border-top: 1px solid #ddd;
    }

    .dropbox {
      width: 180px;
      height: 150px;
      border: 2px dashed #007bff;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      cursor: pointer;
      overflow: hidden;
      transition: 0.3s;
      margin-top: 5px;
    }

    .dropbox:hover {
      background: #f0f8ff;
    }

    .dropbox p {
      font-size: 12px;
      color: #555;
      text-align: center;
      margin: 0;
      padding: 0 5px;
    }

    .dropbox input {
      display: none;
    }

    .dropbox img {
      max-width: 100%;
      max-height: 100%;
      border-radius: 6px;
      margin-top: 5px;
    }

    button {
      margin-top: 15px;
      padding: 8px 20px;
      border: none;
      border-radius: 6px;
      background: #007bff;
      color: white;
      font-size: 14px;
      cursor: pointer;
      display: block;
      width: 100%;
    }

    button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

<form method="POST" action="saved.php" enctype="multipart/form-data">
  <h2>Student Enrollment Form</h2>

  <!-- School Year & Semester -->
  <div class="row">
<div>
  <label>School Year</label>
  <select name="sy" required>
    <option value="">Select School Year</option>
    <option value="2023-2024">2023-2024</option>
    <option value="2024-2025">2024-2025</option>
    <option value="2025-2026">2025-2026</option>
  </select>
</div>
    <div>
      <label>Semester</label>
      <select name="sem" required>
        <option value="">Select</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
      </select>
    </div>
  </div>

 <!-- Course & Major -->
<div class="row">
  <div>
    <label>Course</label>
    <select name="course" id="course" required>
      <option value="">Select Course</option>

      <?php
      $seen = [];
      foreach ($courses as $row) {
        if (!in_array($row['COURSE'], $seen)) {
          $seen[] = $row['COURSE'];
          echo "<option value='{$row['COURSE']}'>{$row['COURSE']}</option>";
        }
      }
      ?>

    </select>
  </div>

  <div>
    <label>Major</label>
    <select name="major" id="major">
      <option value="">Select Major</option>
    </select>
  </div>
</div>

<div class="row">
  <div>
    <label>First Name</label>
    <input type="text" name="fname" required>
  </div>
  <div>
    <label>Middle Name</label>
    <input type="text" name="mname">
  </div>
  <div>
    <label>Last Name</label>
    <input type="text" name="lname" required>
  </div>
</div>

  <!-- Age / Birthdate / Gender -->
  <div class="row">
    <div>
      <label>Birth Date</label>
      <input type="date" id="bdate" name="bdate" required>
    </div>
    <div>
      <label>Age</label>
      <input type="number" id="age" name="age" readonly>
    </div>
    <div>
      <label>Gender</label>
      <select name="gender" required>
        <option value="">Select</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
  </div>

  <!-- Contact Info -->
  <div class="row">
    <div>
      <label>Phone Number</label>
      <input type="text" name="phone" required>
    </div>
    <div>
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
  </div>

    <div class="row">
            <div>
        <label>Religion</label>
		<select name="religion" id="religion" required>
  		<option value="">Select Religion</option>
	</select>
      </div>
	</div>    
	
    <div class="row">
            <div>
        <label>Ethnicity</label>
		<select name="ethnicity" id="ethnicity" required>
  		<option value="">Select Ethnicity</option>
	</select>
      </div>
	</div>


  <!-- Address -->
  <div class="section">
    <h3>Address</h3>
    <div class="row">
      <div>
        <label>Country</label>
        <input type="text" name="country" value="Philippines" readonly>
      </div>
   
      	<div>
  		<label>Region</label>
  			<select name="region" id="region" required>
   			 	<option value="">Loading regions...</option>
  			</select>
	</div>
    </div>

    <div class="row">
            <div>
        <label>City/Municipalities</label>
		<select name="city" id="city" required>
  		<option value="">Select City/Municipalities</option>
	</select>
      </div>

      <div>
        <label>Barangay</label>
        <select name="barangay" id="barangay" required>
          <option value="">Select Barangay</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Image Upload -->
  <div class="section">
    <h3>Upload Image</h3>
    <div id="dropbox" class="dropbox">
      <p>Drag & Drop or Click to Upload</p>
      <input type="file" id="imageInput" name="imageInput" accept="image/*" required>
      <img id="preview" src="" alt="" style="display:none;">
    </div>
  </div>

  <!-- Guardian Section -->
  <div class="section">
    <h3>Guardian Info</h3>
    <div class="row">
      <div>
        <label>Full Name</label>
        <input type="text" name="guardian_name" required>
      </div>
      <div>
        <label>Contact</label>
        <input type="text" name="guardian_contact" required>
      </div>
    </div>
    <div class="row">
      <div style="flex:1">
        <label>Address</label>
        <input type="text" name="guardian_address" required>
      </div>
    </div>
  </div>

  <button type="submit">Save</button>

</form>

<!-- JS Scripts -->
<script>
// Age auto-calc
const birthdateInput = document.getElementById("bdate");
const ageInput = document.getElementById("age");

birthdateInput.max = new Date().toISOString().split("T")[0];

birthdateInput.addEventListener("change", function () {
  const birthDate = new Date(this.value);
  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  ageInput.value = age;
});
//Courses and Major
const coursesData = <?php echo json_encode($courses); ?>;

const courseSelect = document.getElementById("course");
const majorSelect = document.getElementById("major");

courseSelect.addEventListener("change", function () {
  const selectedCourse = this.value;

  majorSelect.innerHTML = '<option value="">Select Major</option>';

  coursesData.forEach(item => {
    if (item.COURSE === selectedCourse && item.MAJOR) {
      const option = document.createElement("option");
      option.value = item.MAJOR;
      option.textContent = item.MAJOR;
      majorSelect.appendChild(option);
    }
  });
});
const regionSelect = document.getElementById("region");
const citySelect = document.getElementById("city");

// Load Regions
fetch("fetch_region.php")
  .then(response => response.json())
  .then(data => {
    regionSelect.innerHTML = '<option value="">Select Region</option>';

    data.forEach(item => {
      const option = document.createElement("option");
      option.value = item.REGION;
      option.textContent = item.REGION;
      regionSelect.appendChild(option);
    });
  })
  .catch(error => {
    console.error("Error loading regions:", error);
    regionSelect.innerHTML = '<option value="">Failed to load</option>';
  });

// Load Cities when Region changes 
regionSelect.addEventListener("change", function () {
  const selectedRegion = this.value;

  citySelect.innerHTML = '<option value="">Loading cities...</option>';

  fetch("fetch_cities.php?r=" + encodeURIComponent(selectedRegion))
    .then(res => res.json())
    .then(data => {
      citySelect.innerHTML = '<option value="">Select City/Municipality</option>';

      data.forEach(item => {
        const option = document.createElement("option");
        option.value = item.CITIES_MUNICIPALITIES;
        option.textContent = item.CITIES_MUNICIPALITIES;
        citySelect.appendChild(option);
      });
    })
    .catch(err => {
      console.error("City load error:", err);
      citySelect.innerHTML = '<option value="">Failed to load</option>';
    });
});
const barangaySelect = document.getElementById("barangay");

const barangays = {
  "General Santos City": ["Apopong","Baluan","Batomelong","Buayan","Bula","Calumpang","City Heights","Conel",
           "Dadiangas East","Dadiangas North","Dadiangas South","Dadiangas West","Fatima","Katangawan",
           "Labangal","Lagao","Ligaya","Olympog","San Isidro","San Jose","Siguel","Sinawal","Tambler","Tinagacan"],

  "Alabel": ["Alegria","Bagacay","Baluntay","Domolok","Kawas","Maribulan","Pag-asa","Poblacion"],

  "Glan": ["Batulaki","Big Margus","Burias","Cablalan","Calabanit","Cross","Gumasa","Poblacion"],

  "Kiamba": ["Badtasan","Datu Dani","Katubao","Kayupo","Lagundi","Lomuyon","Poblacion"],

  "Maasim": ["Amsipit","Colon","Daliao","Kabatiol","Kamanga","Kanalo","Poblacion"],

  "Malapatan": ["Daan Suyan","Kinam","Libi","Lun Padidu","Poblacion","Sapu Masla","Tuyan"],

  "Malungon": ["Ampon","Banate","Datal Batong","Datal Bila","Kibala","Kinapulan","Poblacion"]
};

citySelect.addEventListener("change", function () {
  let selectedCity = this.value.trim().toLowerCase();

  barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

  for (let key in barangays) {
    if (key.toLowerCase() === selectedCity) {
      barangays[key].forEach(function(brgy){
        const option = document.createElement("option");
        option.value = brgy;
        option.textContent = brgy;
        barangaySelect.appendChild(option);
      });
    }
  }
});

const religionSelect = document.getElementById("religion");
const ethnicitySelect = document.getElementById("ethnicity");

// Load Religion and Ethnicity
fetch("fetch_religion.php")
  .then(response => response.json())
  .then(data => {
    religionSelect.innerHTML = '<option value="">Select Religion</option>';

    data.forEach(item => {
      const option = document.createElement("option");
      option.value = item.religion;
      option.textContent = item.religion;
      religionSelect.appendChild(option);
    });
  })
  .catch(error => {
    console.error("Error loading religions:", error);
    religionSelect.innerHTML = '<option value="">Failed to load</option>';
  });

fetch("fetch_ethnicity.php")
  .then(response => response.json())
  .then(data => {
    ethnicitySelect.innerHTML = '<option value="">Select Ethnicity</option>';

    data.forEach(item => {
      const option = document.createElement("option");
      option.value = item.ethnicname;
      option.textContent = item.ethnicname;
      ethnicitySelect.appendChild(option);
    });
  })
  .catch(error => {
    console.error("Error loading ethnicity:", error);
    ethnicitySelect.innerHTML = '<option value="">Failed to load</option>';
  });


// Image drag-drop
const dropbox = document.getElementById("dropbox");
const imageInput = document.getElementById("imageInput");
const preview = document.getElementById("preview");

dropbox.addEventListener("click", ()=> imageInput.click());

imageInput.addEventListener("change", (e)=>{
  const file = e.target.files[0];
  if(file && file.type.startsWith("image/")){
    const reader = new FileReader();
    reader.onload = function(event){
      preview.src = event.target.result;
      preview.style.display="block";
    }
    reader.readAsDataURL(file);
  }
});

dropbox.addEventListener("dragover", e=>{
  e.preventDefault();
  dropbox.style.background = "#f0f8ff";
});

dropbox.addEventListener("dragleave", e=>{
  dropbox.style.background = "transparent";
});

dropbox.addEventListener("drop", e=>{
  e.preventDefault();
  dropbox.style.background = "transparent";
  const file = e.dataTransfer.files[0];
  if(file && file.type.startsWith("image/")){
    imageInput.files = e.dataTransfer.files;
    const reader = new FileReader();
    reader.onload = function(event){
      preview.src = event.target.result;
      preview.style.display="block";
    }
    reader.readAsDataURL(file);
  }
});
</script>

</body>
</html>