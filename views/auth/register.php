<?php include __DIR__ . '/../_head.php' ?>

<form action="/register" method="POST">
    <h1>Sign up</h1>

    <?php if (has_errors()): ?>
        <p>
            <h2>Errors</h2>

            <ul>
                <?php foreach (errors() as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach ?>
            </ul>
        </p>
    <?php endif ?>

    <p>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= e(old('name')) ?>">
    </p>

    <p>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?= e(old('email')) ?>">
    </p>

    <p>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= e(old('password')) ?>">
    </p>

    <p>
        <a href="/login">Log in</a>&nbsp
        <button>Sign up</button>
    </p>
</form>

<?php include __DIR__ . '/../_tail.php' ?>