<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

//clear notifications and alert user that they are cleared
if (isset($_GET['Clear_Notifications'])) {
  $id = intval($_GET['Clear_Notifications']);
  $adn = "DELETE FROM  iB_notifications  WHERE notification_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Notifications Cleared";
  } else {
    $err = "Try Again Later";
  }
}
/*
    get all dashboard analytics 
    and numeric values from distinct 
    tables
    */

//return total number of ibank clients
$result = "SELECT count(*) FROM iB_clients";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBClients);
$stmt->fetch();
$stmt->close();

//return total number of iBank Staffs
$result = "SELECT count(*) FROM iB_staff";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBStaffs);
$stmt->fetch();
$stmt->close();

//return total number of iBank Account Types
$result = "SELECT count(*) FROM iB_Acc_types";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_AccsType);
$stmt->fetch();
$stmt->close();

//return total number of iBank Accounts
$result = "SELECT count(*) FROM iB_bankAccounts";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_Accs);
$stmt->fetch();
$stmt->close();

//return total number of iBank Deposits
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  tr_type = 'Deposit' ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_deposits);
$stmt->fetch();
$stmt->close();

//return total number of iBank Withdrawals
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  tr_type = 'Withdrawal' ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_withdrawal);
$stmt->fetch();
$stmt->close();



//return total number of iBank Transfers
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  tr_type = 'Transfer' ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_Transfers);
$stmt->fetch();
$stmt->close();

//return total number of  iBank initial cash->balances
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($acc_amt);
$stmt->fetch();
$stmt->close();
//Get the remaining money in the accounts
$TotalBalInAccount = ($iB_deposits)  - (($iB_withdrawal) + ($iB_Transfers));


//ibank money in the wallet
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions ";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($new_amt);
$stmt->fetch();
$stmt->close();
//Withdrawal Computations

?>
<!-- Log on to codeastro.com for more projects! -->
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

  <div class="wrapper">
    <!-- Navbar -->
    <?php include("dist/_partials/nav.php"); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Admin Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">

            <!-- ./ iBank Clients -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Clients</span>
                  <span class="info-box-number">
                    <?php echo $iBClients; ?>
                  </span>
                </div>
              </div>
            </div>
            <!-- ./ iBank Clients -->

            <!--iBank Staffs-->
           <!--- <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-tie"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Staffs</span>
                  <span class="info-box-number">
                    <?php echo $iBStaffs; ?>
                  </span>
                </div>
              </div>
            </div>-->
            <!-- ./ iBank Staffs-->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <!--Ibank Acc types-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-briefcase"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Account Types</span>
                  <span class="info-box-number"><?php echo $iB_AccsType; ?></span>
                </div>
              </div>
            </div>
            <!-- /.iBank Acc types -->

            <!--iBank Accounts-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Accounts</span>
                  <span class="info-box-number"><?php echo $iB_Accs; ?></span>
                </div>
              </div>
            </div>
            <!-- iBank Acc-->
          </div>

          <div class="row">
            <!--iBank Deposits -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-upload"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Deposits</span>
                  <span class="info-box-number">
                    $ <?php echo $iB_deposits; ?>
                  </span>
                </div>
              </div>
            </div>
            <!----./ iBank Deposits-->

            <!--iBank Withdrwals-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-download"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Withdrawals</span>
                  <span class="info-box-number">$ <?php echo $iB_withdrawal; ?> </span>
                </div>
              </div>
            </div>
            <!-- Withdrawals-->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <!--Transfers-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-random"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Transfers</span>
                  <span class="info-box-number">$ <?php echo $iB_Transfers; ?></span>
                </div>
              </div>
            </div>
            <!-- /.Transfers-->

            <!--Balances-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-money-bill-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Wallet Balance</span>
                  <span class="info-box-number">$ <?php echo $TotalBalInAccount; ?></span>
                </div>
              </div>
            </div>
            <!-- ./Balances-->
          </div><!-- Log on to codeastro.com for more projects! -->


          <!-- /.row -->

          <!-- Main row -->
         
          <!-- /.row -->
        </div>
        <!--/. container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?php include("dist/_partials/footer.php"); ?>

  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="dist/js/demo.js"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="plugins/raphael/raphael.min.js"></script>
  <script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>

  <!-- PAGE SCRIPTS -->
  <script src="dist/js/pages/dashboard2.js"></script>

  <!--Load Canvas JS -->
  <script src="plugins/canvasjs.min.js"></script>
  <!--Load Few Charts-->
  <script>
    window.onload = function() {

      var Piechart = new CanvasJS.Chart("PieChart", {
        exportEnabled: false,
        animationEnabled: true,
        title: {
          text: "Accounts Per Acc Types "
        },
        legend: {
          cursor: "pointer",
          itemclick: explodePie
        },
        data: [{
          type: "pie",
          showInLegend: true,
          toolTipContent: "{name}: <strong>{y}%</strong>",
          indexLabel: "{name} - {y}%",
          dataPoints: [{
              y: <?php
                  //return total number of accounts opened under savings acc type
                  $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Savings' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($savings);
                  $stmt->fetch();
                  $stmt->close();
                  echo $savings;
                  ?>,
              name: "Savings Acc",
              exploded: true
            },

            {
              y: <?php
                  //return total number of accounts opened under  Retirement  acc type
                  $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type =' Retirement' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Retirement);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Retirement;
                  ?>,
              name: " Retirement Acc",
              exploded: true
            },

            {
              y: <?php
                  //return total number of accounts opened under  Recurring deposit  acc type
                  $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Recurring deposit' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Recurring);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Recurring;
                  ?>,
              name: "Recurring deposit Acc ",
              exploded: true
            },

            {
              y: <?php
                  //return total number of accounts opened under  Fixed Deposit Account deposit  acc type
                  $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Fixed Deposit Account' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Fixed);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Fixed;
                  ?>,
              name: "Fixed Deposit Acc",
              exploded: true
            },

            {
              y: <?php
                  //return total number of accounts opened under  Current account deposit  acc type
                  $result = "SELECT count(*) FROM iB_bankAccounts WHERE  acc_type ='Current account' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Current);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Current;
                  ?>,
              name: "Current Acc",
              exploded: true
            }
          ]
        }]
      });

      var AccChart = new CanvasJS.Chart("AccountsPerAccountCategories", {
        exportEnabled: false,
        animationEnabled: true,
        title: {
          text: "Transactions"
        },
        legend: {
          cursor: "pointer",
          itemclick: explodePie
        },
        data: [{
          type: "pie",
          showInLegend: true,
          toolTipContent: "{name}: <strong>{y}%</strong>",
          indexLabel: "{name} - {y}%",
          dataPoints: [{
              y: <?php
                  //return total number of transactions under  Withdrawals
                  $result = "SELECT count(*) FROM iB_Transactions WHERE  tr_type ='Withdrawal' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Withdrawals);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Withdrawals;
                  ?>,
              name: "Withdrawals",
              exploded: true
            },

            {
              y: <?php
                  //return total number of transactions under  Deposits
                  $result = "SELECT count(*) FROM iB_Transactions WHERE  tr_type ='Deposit' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Deposits);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Deposits;
                  ?>,
              name: "Deposits",
              exploded: true
            },

            {
              y: <?php
                  //return total number of transactions under  Deposits
                  $result = "SELECT count(*) FROM iB_Transactions WHERE  tr_type ='Transfer' ";
                  $stmt = $mysqli->prepare($result);
                  $stmt->execute();
                  $stmt->bind_result($Transfers);
                  $stmt->fetch();
                  $stmt->close();
                  echo $Transfers;
                  ?>,
              name: "Transfers",
              exploded: true
            }

          ]
        }]
      });
      Piechart.render();
      AccChart.render();
    }

    function explodePie(e) {
      if (typeof(e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
      } else {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
      }
      e.chart.render();

    }
  </script>

</body>

</html>