<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit; // Ensure no further code is executed
}
// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");



include '../includes/db.php';
include '../includes/header.php';
include '../superadmin/log_activity.php';

// Handle form submission to add a new branch
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $branch_name = $_POST['branch_name'];
    $branch_address = $_POST['branch_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $branch_manager = $_POST['branch_manager'];
    $date_established = $_POST['date_established'];
    $status = $_POST['status'];
    $total_employees = $_POST['total_employees'];

    // Check if all required fields are filled
    if (!empty($branch_name) && !empty($branch_address) && !empty($city) && !empty($state) && 
        !empty($contact_number) && !empty($email) && !empty($date_established) && 
        !empty($status) && !empty($total_employees)) {

        // Prepare the SQL query to insert the new branch
        $sql_insert = "INSERT INTO branches (branch_name, branch_address, city, state, contact_number, email, branch_manager, date_established, status, total_employees) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        
        // Bind the parameters to the prepared statement
        $stmt_insert->bind_param("sssssssssi", $branch_name, $branch_address, $city, $state, 
            $contact_number, $email, $branch_manager, $date_established, $status, $total_employees);

        // Execute the query
        if ($stmt_insert->execute()) {
            if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                logActivity($_SESSION['user_id'], $_SESSION['username'], "Added a new branch : $branch_name");
            }
            $_SESSION['addbranch']="<div class='alert alert-success'>Added Branch successfully!</div>";
            // Redirect to branches.php after successful insertion
            echo "<script>window.location.href = '../superadmin/branches.php';</script>";
            exit; // Make sure to exit after the redirect
        } else {
            $_SESSION['addbranch']= "<script>alert('Error adding branch: " . $conn->error . "');</script>";
        }

    } else {
        echo "<script>alert('Please fill out all the fields.');</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Branch</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Dashboard Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky"
                style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <form method="POST" action="" class="p-4 bg-light rounded shadow-sm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Branch Name</label>
                            <input type="text" maxlength="30" class="form-control" id="branch_name" name="branch_name"
                                placeholder="Enter branch name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="branch_address" class="form-label">Branch Address</label>
                            <input type="text" class="form-control" id="branch_address" name="branch_address"
                                placeholder="Enter branch address" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state" name="state" required>
                                <option value="" disabled selected>Select a state</option>
                                <!-- List of states -->
                                <!-- You can add your own list here -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="district" class="form-label">District</label>
                            <select class="form-select" id="district" name="city" required>
                                <option value="" disabled selected>Select a district</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number"
                                pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number" maxlength="10"
                                inputmode="numeric" placeholder="Enter 10-digit contact number"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter branch email address" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="branch_manager" class="form-label">Branch Manager</label>
                            <input type="text" class="form-control" id="branch_manager" name="branch_manager"
                                placeholder="Enter branch manager's name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_established" class="form-label">Date Established</label>
                            <input type="date" class="form-control" id="date_established" name="date_established"
                                required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="total_employees" class="form-label">Total Employees</label>
                            <input type="number" class="form-control" id="total_employees" name="total_employees"
                                placeholder="Enter total employees count" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Branch</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php
include '../includes/footer.php';
?>

</html>

<script>
    // Object mapping states to districts
    const stateDistricts = {
        "Andhra Pradesh": ["Anantapur", "Chittoor", "East Godavari", "Guntur", "Kadapa", "Krishna", "Kurnool", "Prakasam", "Srikakulam", "Visakhapatnam", "Vizianagaram", "West Godavari"],
        "Arunachal Pradesh": ["Tawang", "West Kameng", "East Kameng", "Papum Pare", "Kurung Kumey", "Kra Daadi", "Lower Subansiri", "Upper Subansiri", "West Siang", "East Siang", "Upper Siang", "Lower Siang"],
        "Assam": ["Baksa", "Barpeta", "Biswanath", "Bongaigaon", "Cachar", "Charaideo", "Chirang", "Darrang", "Dhemaji", "Dhubri", "Dibrugarh", "Goalpara", "Golaghat", "Hailakandi"],
        "Bihar": ["Araria", "Arwal", "Aurangabad", "Banka", "Begusarai", "Bhagalpur", "Bhojpur", "Buxar", "Darbhanga", "East Champaran", "Gaya", "Gopalganj", "Jamui", "Jehanabad", "Khagaria"],
        "Chhattisgarh": ["Balod", "Baloda Bazar", "Balrampur", "Bastar", "Bemetara", "Bijapur", "Bilaspur", "Dantewada", "Dhamtari", "Durg", "Gariaband", "Janjgir-Champa", "Jashpur"],
        "Goa": ["North Goa", "South Goa"],
        "Gujarat": ["Ahmedabad", "Amreli", "Anand", "Aravalli", "Banaskantha", "Bharuch", "Bhavnagar", "Botad", "Chhota Udaipur", "Dahod", "Dang", "Devbhoomi Dwarka", "Gandhinagar"],
        "Haryana": ["Ambala", "Bhiwani", "Charkhi Dadri", "Faridabad", "Fatehabad", "Gurgaon", "Hisar", "Jhajjar", "Jind", "Kaithal", "Karnal", "Kurukshetra", "Mahendragarh", "Nuh"],
        "Himachal Pradesh": ["Bilaspur", "Chamba", "Hamirpur", "Kangra", "Kinnaur", "Kullu", "Lahaul and Spiti", "Mandi", "Shimla", "Sirmaur", "Solan", "Una"],
        "Jharkhand": ["Bokaro", "Chatra", "Deoghar", "Dhanbad", "Dumka", "East Singhbhum", "Garhwa", "Giridih", "Godda", "Gumla", "Hazaribagh", "Jamtara", "Khunti", "Koderma"],
        "Karnataka": ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum", "Bellary", "Bidar", "Bijapur", "Chamarajanagar", "Chikkaballapur", "Chikmagalur", "Chitradurga", "Davangere"],
        "Kerala": ["Alappuzha", "Ernakulam", "Idukki", "Kannur", "Kasaragod", "Kollam", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta", "Thiruvananthapuram", "Thrissur"],
        "Madhya Pradesh": ["Agar Malwa", "Alirajpur", "Anuppur", "Ashoknagar", "Balaghat", "Barwani", "Betul", "Bhind", "Bhopal", "Burhanpur", "Chhatarpur", "Chhindwara", "Damoh", "Datia"],
        "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik", "Aurangabad", "Kolhapur", "Latur", "Solapur", "Thane", "Ahmednagar", "Amravati", "Beed", "Bhandara", "Chandrapur"],
        "Manipur": ["Bishnupur", "Chandel", "Churachandpur", "Imphal East", "Imphal West", "Jiribam", "Kakching", "Kamjong", "Kangpokpi", "Noney", "Pherzawl", "Senapati", "Tamenglong"],
        "Meghalaya": ["East Garo Hills", "East Jaintia Hills", "East Khasi Hills", "North Garo Hills", "Ri Bhoi", "South Garo Hills", "South West Garo Hills", "South West Khasi Hills", "West Garo Hills"],
        "Mizoram": ["Aizawl", "Champhai", "Kolasib", "Lawngtlai", "Lunglei", "Mamit", "Saiha", "Serchhip"],
        "Nagaland": ["Dimapur", "Kiphire", "Kohima", "Longleng", "Mokokchung", "Mon", "Peren", "Phek", "Tuensang", "Wokha", "Zunheboto"],
        "Odisha": ["Angul", "Balangir", "Balasore", "Bargarh", "Bhadrak", "Boudh", "Cuttack", "Deogarh", "Dhenkanal", "Gajapati", "Ganjam", "Jagatsinghpur", "Jajpur"],
        "Punjab": ["Amritsar", "Barnala", "Bathinda", "Faridkot", "Fatehgarh Sahib", "Fazilka", "Ferozepur", "Gurdaspur", "Hoshiarpur", "Jalandhar", "Kapurthala", "Ludhiana"],
        "Rajasthan": ["Ajmer", "Alwar", "Banswara", "Baran", "Barmer", "Bharatpur", "Bhilwara", "Bikaner", "Bundi", "Chittorgarh", "Churu", "Dausa", "Dholpur", "Dungarpur"],
        "Sikkim": ["East Sikkim", "North Sikkim", "South Sikkim", "West Sikkim"],
        "Tamil Nadu": ["Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kallakurichi", "Kancheepuram", "Karur", "Krishnagiri", "Madurai", "Nagapattinam"],
        "Telangana": ["Adilabad", "Bhadradri Kothagudem", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar", "Jogulamba Gadwal", "Kamareddy", "Karimnagar", "Khammam", "Kumuram Bheem", "Mahabubabad", "Mahbubnagar"],
        "Tripura": ["Dhalai", "Gomati", "Khowai", "North Tripura", "Sepahijala", "South Tripura", "Unakoti", "West Tripura"],
        "Uttar Pradesh": ["Agra", "Aligarh", "Prayagraj", "Ambedkar Nagar", "Amethi", "Amroha", "Auraiya", "Azamgarh", "Baghpat", "Bahraich", "Ballia", "Balrampur", "Banda", "Barabanki"],
        "Uttarakhand": ["Almora", "Bageshwar", "Chamoli", "Champawat", "Dehradun", "Haridwar", "Nainital", "Pauri Garhwal", "Pithoragarh", "Rudraprayag", "Tehri Garhwal", "Udham Singh Nagar", "Uttarkashi"],
        "West Bengal": ["Alipurduar", "Bankura", "Birbhum", "Cooch Behar", "Dakshin Dinajpur", "Darjeeling", "Hooghly", "Howrah", "Jalpaiguri", "Jhargram", "Kalimpong", "Kolkata", "Malda", "Murshidabad"]
    };

    const stateSelect = document.getElementById('state');

    // Populate the state dropdown
    for (let state in stateDistricts) {
        let option = document.createElement('option');
        option.value = state;
        option.text = state;
        stateSelect.appendChild(option);
    }

    // Get references to the state and district dropdowns
    const districtSelect = document.getElementById('district');

    // Event listener for state dropdown change
    stateSelect.addEventListener('change', function () {
        const selectedState = this.value;
        const districts = stateDistricts[selectedState] || [];

        // Clear any existing options in district dropdown
        districtSelect.innerHTML = '<option value="" disabled selected>Select a district</option>';

        // Populate the district dropdown based on selected state
        districts.forEach(function (district) {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    });

</script>