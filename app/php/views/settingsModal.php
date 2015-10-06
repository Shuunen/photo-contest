<?php

  $settings = $app->getAllSettings();

?>

<div id="settingsModal" tabindex="-1" role="dialog" class="modal fullscreen fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="settings-form form-horizontal">
                  <fieldset>

                    <!-- Form Name -->
                    <legend>Application settings</legend>

                    <div class="message"></div>

                    <?php foreach($settings as $setting):?>

                      <div class="form-group">
                        <label class="col-md-4 control-label" for="<?php print $setting->settingsid?>"><?php print $setting->settingslabel;?> : </label>
                        <div class="col-md-5">
                          <input id="<?php print $setting->settingsid?>" name="<?php print $setting->settingsid?>" type="text" value="<?php print $setting->settingsvalue;?>" class="form-control input-md" required="">
                        </div>
                      </div>

                    <?php endforeach;?>

                    <!-- Button -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="save"></label>
                      <div class="col-md-4">
                        <button id="save" name="save" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                    <input type="hidden" name="type" value="setSettings">

                  </fieldset>
                </form>
            </div>
            <button type="button" class="btn btn-default refresh-button" event-emitter data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
