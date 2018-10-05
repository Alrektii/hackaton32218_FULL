<div class="login_form" style="display: none">
    <form action='php/login.php' method='post'>
        <h1>Login</h1>
        <br>
        <input type='text' class='form-control' id="us" name='username' placeholder='username' autocomplete='off' >
        <br>
        <input type='password' id="pw" class='form-control' name='password' placeholder='password' autocomplete='off' >
        <br>
        <button type='submit' id="login" name='login' class="f-btn">Login</button>
        <button type='submit' id="register" name='Register' class="f-btn">Register</button>
    </form>
</div>
<div style="display: none" class="regform">
    <form action='php/login.php' method='post'>
        <h1>Register</h1>
        <input type='text' class='form-control' id="regus" name='username' placeholder='username' autocomplete='off' >
        <input type='password' id="regpw" class='form-control' name='password' placeholder='password' autocomplete='off' >
        <input type='password' id="regrepw" class='form-control' name='repassword' placeholder='repeat password' autocomplete='off' >
        <input type='email' id="regemail" class='form-control' name='password' placeholder='Email adress' autocomplete='off' >
        <select id="sector">
            <?php


                $sth = $pdo->prepare("SELECT * FROM bedrijfsectors");
            $sth->execute();

            while($row = $sth->fetch()){
            ?>
            <option  name="sectoren" value="<?= $row["sectorNaam"]?>"><?= $row["sectorNaam"]?></option>
            <?php
                }

                ?>
        </select>
        <button type='submit' id='registerSubmit' name='Register' class="f-btn">Register</button>
        <button type='submit' id='back' name='back' class="f-btn">Back</button>
    </form>
</div>

<div class="home">
    <h2>Brabant 2 Go</h2>
    <button id="logout" class="f-btn">Logout</button>
    <button id="upload_new_product" class="f-btn">Upload product!</button>
    <button id="upload_product_load" class="f-btn">Load products</button>
    <div id="print_results"></div>
</div>

<div class="new_product_form" style="display: none">
    <h2>New product</h2>
    <br />
    <form>
        <input class='form-control' type="text" name="product_name" id="product_name" placeholder="Product name">
        <br />
        <textarea class='form-control' id="product_desc" rows="4" cols="40" placeholder="Description product....."></textarea>
        <br />
        <div class="upload-btn-wrapper">
            <button class="btn">Upload a file</button>
            <input type="file" id="product_image">
        </div>
        <br />
        <br />
        <button id="upload_product" class="f-btn">Add</button>
        <button id="upload_product_back" class="f-btn">Cancel</button>
    </form>
</div>