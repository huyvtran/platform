<div class="app-login">

    <div class="app-header">
        <p class="app-title">{{ title }}</p>
    </div>
    <div class="app-body">
    <span class="help-block">
        <?= $this->Session->flash(); ?>
    </span>
        <div class="form-group">
            <?= $textInfo ?>
        </div>
    </div>

    <div class="app-info">
        <span>Game Version: 1.0</span>
        <span class="text-right">SDK Version: 0.1</span>
    </div>


</div>