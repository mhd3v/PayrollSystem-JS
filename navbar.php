<?php 
if(session_status() == PHP_SESSION_NONE) 
session_start();
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">

  <a class="navbar-brand abs" href="index.php">Payroll System</a>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
      <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse" id="collapsingNavbar">

    <ul class="nav navbar-nav">

        <?php if(isset($_SESSION['user'])) {?>

        <li class="nav-item dropdown">
          <a style="color:white;" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown">
            <i class="fas fa-user-circle mr-1" style="font-size:1.2em"></i><?=$_SESSION['user']?>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </li>

        <?php } else{?>

        <li class="nav-item active">
          <a class="nav-link" href="index.php">Login</a>
        </li>
        
        <?php } ?>

    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        
        <li class="nav-item dropdown">
          <a style="color:white;" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
            HR
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="add_employee.php">Add Employee</a>
            <a class="dropdown-item" href="view_enrollments.php">View Enrollments</a>
            <a class="dropdown-item" href="import_employees.php">Bulk Import Employees</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a style="color:white;" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
            Payroll
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="compensations.php">Compensations</a>
            <a class="dropdown-item" href="loans.php">Loans</a>
            <a class="dropdown-item" href="leaves.php">Leaves</a>
            <a class="dropdown-item" href="generate_payslip.php">Generate Payslip</a>
            <a class="dropdown-item" href="monthly_salary.php">Monthly Salary</a>
          </div>
        </li>

        <li class="nav-item active">
          <a class="nav-link">Feedback</a>
        </li>
        
    </ul>

  </div>

</nav>
