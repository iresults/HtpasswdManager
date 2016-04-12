<div class="form-group row">
    <label for="username" class="col-md-2 form-control-label">Benutzername</label>
    <div class="col-md-10">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
               pattern="[a-zA-Z0-9]+"
               value="{{ $username or '' }}">
        <small>Erlaubt sind Klein- und Gross-Buchstaben (a-zA-Z) und Zahlen (0-9). Leer- und Sonderzeichen sind ungültig.</small>
    </div>
</div>
<div class="form-group row">
    <label for="password" class="col-md-2 form-control-label">Password</label>
    <div class="col-md-10">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-offset-2 col-md-10">
        <button type="submit" class="btn btn-secondary btn-block">Speichern</button>
    </div>
</div>