<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CI Backup Module</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>

<body>

    <div id="container">
        <div class="card text-center my-5">
            <div class="card-header">
                Backup Module Using CodeIgniter And Google Drive API (Service Account)
            </div>
            <div class="card-body">

                <button type="button" onclick="backupNow()" class="btn btn-success">Backup Now!</button>

                <button type="button" data-toggle="modal" data-target="#SetupAutoBackup" class="btn btn-info">Setup Auto
                    Backup</button>


            </div>
        </div>

    </div>
    <!-- Setupcron -->
    <div class="modal fade" id="SetupAutoBackup" tabindex="-1" role="dialog" aria-labelledby="Setup" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">

                <div class="modal-header text-center" style="display: block !important;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                            class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title text-center " id="Heading">Setup Auto Backup</h4>
                </div>
                <form method="POST" role="form" class="setupcron-form">
                    <div class="modal-body">
                        <div class="row">
                            <div class="input-group mb-3 mx-5">
                                <select class="custom-select" id="inputGroupSelect02" required>
                                    <option id="emptyres" value="" selected>Daily, Minutes...</option>
                                    <option id="Daily" value="Daily">Daily</option>
                                    <option id="Minutes" value="Minutes">Minutes</option>
                                </select>
                                <div class="input-group-append">
                                    <label class="input-group-text" for="inputGroupSelect02">Schedule Type</label>
                                </div>
                            </div>
                            <div class="input-group mb-3 mx-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend3">Backup Path:</span>
                                    </div>
                                    <input type="text" class="form-control google-backup-path" id="google-backup-path"
                                        placeholder="Google Drive Path" aria-describedby="inputGroupPrepend3" required>

                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Enable Auto Backup
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="col-12 text-center">

                                <label lass="bl-label">Time</label>
                                <br>
                                <input type="number" class="cronHours" id="cronHours" min="0" max="23" placeholder="23">
                                :
                                <input type="number" class="cronMinutes" id="cronMinutes" min="0" max="59"
                                    placeholder="00">
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-warning btn-lg">Save</button>
                    </div>
                </form>
            </div>

            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</body>
<!-- Javascript -->
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Call internal js -->
<script src="<?=base_url()?>assets/js/main.js"></script>

</html>