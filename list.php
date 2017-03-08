<!-- Pi-hole: A black hole for Internet advertisements
*    (c) 2017 Pi-hole, LLC (https://pi-hole.net)
*    Network-wide ad blocking via your own hardware.
*
*    This file is copyright under the latest version of the EUPL.
*    Please see LICENSE file for your rights under this license. -->
<?php
require "scripts/pi-hole/php/header.php";

$list = $_GET['l'];

if ($list !== "white" && $list !== "black") {
    echo "Invalid list parameter";
    require "footer.php";
    die();
}

function getFullName()
{
    global $list;
    if ($list == "white")
        echo "Whitelist";
    else
        echo "Blacklist";
}

?>
<!-- Send list type to JS -->
<div id="list-type" hidden><?php echo $list ?></div>

<!-- Title -->
<div class="page-header">
    <h1><?php getFullName(); ?></h1>
</div>

<div class="row">
    <!-- Domain Input -->
    <div class="form-group col-sm-8 <?php echo $list ?>">
        <div class="input-group">
            <label for="domain">Add a domain</label>
            <input id="domain" type="text" class="form-control" placeholder="example.com or sub.example.com">
            <span class="input-group-btn">
                <?php if ($list === "black") : ?>
                    <button id="btnAdd" class="btn btn-default" type="button">Add (exact)</button>
                    <button id="btnAddWildcard" class="btn btn-default" type="button">Add (wildcard)</button>
                <?php else : ?>
                    <button id="btnAdd" class="btn btn-default" type="button">Add</button>
                <?php endif ?>
                <button id="btnRefresh" class="btn btn-default" type="button"><i class="fa fa-refresh"></i></button>
            </span>
        </div>
    </div>

    <div class="form-group col-sm-4">
        <label for="uploadListFile">Upload list of domains</label>
        <input type="file" id="uploadListFile" data-type="<?php echo $list ?>" class="form-control">

        <div class="progress" style="display: none;">
            <div id="uploadProgressBar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
    </div>
</div>

<?php if ($list === "white") { ?>
    <p>Note that whitelisting domains which are blocked using the wildcard method won't work.</p><?php } ?>

<!-- Alerts -->
<div id="alInfo" class="alert alert-info alert-dismissible fade in" role="alert" hidden="true">
    <button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    Adding to the <?php getFullName(); ?>...
</div>
<div id="alSuccess" class="alert alert-success alert-dismissible fade in" role="alert" hidden="true">
    <button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    Success! The list will refresh.
</div>
<div id="alFailure" class="alert alert-danger alert-dismissible fade in" role="alert" hidden="true">
    <button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    Failure! Something went wrong.<br/><span id="err"></span>
</div>

<!-- Domain List -->
<?php if ($list === "black") { ?>
    <h3>Exact blocking</h3>
<?php } ?>
<ul class="list-group" id="list"></ul>
<?php if ($list === "black") { ?>
    <h3>Wildcard blocking</h3>
    <ul class="list-group" id="list-wildcard"></ul>
<?php } ?>

<?php
require "scripts/pi-hole/php/footer.php";
?>

<script src="scripts/pi-hole/js/list.js?t=<?php echo time() ?>"></script>
