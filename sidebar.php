<head>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <div class="profile-sidebar">
        <div class="profile-userpic">
            <img src="img/user.png" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
            <div class="profile-usertitle-name"><?php echo $user['name'];?></div>
            <div class="profile-usertitle-status"><span class="indicator label-success"></span><?php echo $user['role'];?></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="divider"></div>
    <ul class="nav menu">
    <?php 
        if (isset($_GET['dashboard'])){ ?>
            <li class="active">
                <a href="index.php?dashboard"><em class="fa fa-Home">&nbsp;</em>
                    Dashboard
                </a>
            </li>
        <?php } else{?>
            <li>
                <a href="index.php?dashboard"><em class="fa fa-home">&nbsp;</em>
                    Dashboard
                </a>
            </li>
        <?php }
        if (isset($_GET['reservation'])){ ?>
            <li class="active">
            <a href="index.php?reservation"><em class="fa fa-calendar">&nbsp;</em>
                    Reservation
                </a>
            </li>
        <?php } else{?>
            <li>
            <a href="index.php?reservation"><em class="fa fa-calendar">&nbsp;</em>
                    Reservation
                </a>
            </li>
        <?php }
        if (isset($_GET['room_mang'])){ ?>
            <li class="active">
                <a href="index.php?room_mang"><em class="fa fa-bed">&nbsp;</em>
                    Manage Rooms
                </a>
            </li>
        <?php } else{?>
            <li>
            <a href="index.php?room_mang"><em class="fa fa-bed">&nbsp;</em>
                    Manage Rooms
                </a>
            </li>
        <?php }
        if (isset($_GET['laundry'])){ ?>
            <li class="active">
                <a href="index.php?laundry"><em class="fa fa-tshirt ">&nbsp;</em>
                 Laundry Section
                </a>
            </li>
        <?php } else{?>
            <li>
                <a href="index.php?laundry"><em class="fa fa-tshirt ">&nbsp;</em>
                    Laundry Section
                </a>
            </li>
        <?php }
        if (isset($_GET['Meals'])){ ?>
            <li class="active">
                <a href="index.php?Meals"><em class="fa fa-utensils">&nbsp;</em>
                    Meals
                </a>
            </li>
        <?php } else{?>
            <li>
                <a href="index.php?Meals"><em class="fa fa-utensils">&nbsp;</em>
                    Meals
                </a>
            </li>
        <?php }
        ?>

        <?php
        if (isset($_GET['reports'])){ ?>
            <li class="active">
                <a href="index.php?reports"><em class="fa fa-credit-card">&nbsp;</em>
                    Reports
                </a>
            </li>
        <?php } else{?>
        <li>
            <a href="index.php?reports"><em class="fa fa-credit-card">&nbsp;</em>
                Reports
            </a>
        </li>
<?php }
if (isset($_GET['Setup'])){ ?>
            <li class="active">
                <a href="index.php?setup"><em class="fa fa-toolbox">&nbsp;</em>
                    Setup
                </a>
            </li>
        <?php } else{?>
            <li>
                <a href="index.php?setup"><em class="fa fa-toolbox">&nbsp;</em>
                    Setup
                </a>
            </li>
        <?php }
    ?>

        
    </ul>
</div><!--/.sidebar-->