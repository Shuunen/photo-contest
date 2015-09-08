<?php $userPhotos = $app->db->select("photos", "userId", $app->currentUser['id']) ?>
<nav class="navbar navbar-default">
    <div class="container-fluid">

        <p class="navbar-text">Available actions</p>

        <ul class="nav navbar-nav">
            <?php if($app->isAdmin): ?>
                <li><a href="#">Approve photos <span class="badge">42</span></a></li>
                <li><a href="#">See results <span class="badge">14</span></a></li>
            <?php endif; ?>
            <li><a href="#" data-toggle="modal" data-target="#myPhotosModal">My photos <span class="badge"><?php echo count($userPhotos) ?></span></a></li>
            <li><a href="#" data-toggle="modal" data-target="#uploadModal">Submit photos</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $app->currentUser['name'] ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#" id="logoutLink">Logout</a></li>
                </ul>
            </li>
        </ul>

    </div>
    <!-- /.container-fluid -->
</nav>
