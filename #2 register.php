<?php include 'header.php'; ?>

<div class="card">
    <h2>Create an Account</h2>

    <form action="" method="POST">

        <label>Full Name</label>
        <input type="text" name="fullname" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login here</a>
    </p>
</div>

</div>

</body>
</html>