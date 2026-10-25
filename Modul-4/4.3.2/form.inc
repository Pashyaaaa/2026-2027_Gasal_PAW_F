<fieldset>
  <legend>Person Details</legend>
  <!-- action dikosongkan agar merujuk ke dirinya sendiri -->
  <form name="myForm" action="" method="POST">
    <div class="field">
      <label for="surname">Surname</label>
      <input
        type="text"
        name="surname"
        size="31"
        value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>"
      />
    </div>
    <div class="field">
      <label for="email">Email address</label>
      <input
        type="text"
        name="email"
        size="31"
        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
      />
    </div>
    <!-- (input password biasanya tidak dibuat sticky kak) -->
    <div class="field">
      <label for="passwd">Password</label>
      <input type="password" name="passwd" size="31" />
    </div>
    <div class="field">
      <label>&nbsp;</label>
      <input type="submit" value="Submit" name="submit" />
      <input type="reset" value="Reset" name="reset" />
    </div>
  </form>
</fieldset>
