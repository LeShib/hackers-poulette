<?php
    session_start();
    include 'config.php';
    
    // Génération et stockage du jeton CSRF
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script defer src="script/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
    <title>Formulaire de contact</title>
</head>
<body class="bg-gradient-to-b from-gray-800 to-gray-900">
    <h1 class="text-2xl flex justify-center text-center mt-1 text-white">Contact form</h1>
    <form class="m-5" action="confirm.php" method="post" id="contact-form" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
            <div>
                <label class="text-white dark:text-gray-200" for="nom">Last name</label>
                <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring" type="text" id="nom" name="nom" minlength="2" maxlength="255" required>
                <?php if (isset($_SESSION['errors']['nom'])) : ?>
                    <p class="text-xs text-white"><?php echo $_SESSION['errors']['nom']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="text-white dark:text-gray-200" for="prenom">First name</label>
                <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring" type="text" id="prenom" name="prenom" minlength="2" maxlength="255" required>
                <?php if (isset($_SESSION['errors']['prenom'])) : ?>
                    <p class="text-xs text-white"><?php echo $_SESSION['errors']['prenom']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-white">Image</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-white" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">
                                <span class="pl-1 text-white">Upload a file</span>
                                <input class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500" type="file" id="file" name="file" accept=".jpg, .jpeg, .png, .gif" maxlength="2097152">
                                <p class="pl-1 text-white">or drag and drop</p>
                            </label>                  
                        </div>
                        <p class="text-xs text-white">PNG, JPG, JPEG, GIF up to 2MB</p>
                        <?php if (isset($_SESSION['errors']['file'])) : ?>
                            <p class="text-xs text-white"><?php echo $_SESSION['errors']['file']; ?></p>
                        <?php endif; ?>  
                    </div>
                </div>
            </div>
            <div>
                <label class="text-white dark:text-gray-200" for="description">Description</label>
                <textarea class="block w-full px-6 py-2.5 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring" id="description" name="description" minlength="2" maxlength="1000" rows="6" required></textarea>
                <?php if (isset($_SESSION['errors']['description'])) : ?>
                    <p class="text-xs text-white"><?php echo $_SESSION['errors']['description']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="text-white dark:text-gray-200" for="mail">Mail</label>
                <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring" type="email" id="mail" name="mail" minlength="2" maxlength="255" required>
                <?php if (isset($_SESSION['errors']['mail'])) : ?>
                    <p class="text-xs text-white"><?php echo $_SESSION['errors']['mail']; ?></p>
                <?php endif; ?>
            </div>
            <div class="flex justify-center">
                <!-- Captcha avec reCAPTCHA v2 -->
                <div class="g-recaptcha" data-sitekey="6Lf8u7ImAAAAAJlx1vVVEEzSmrC0tAFuCmBTdFWk"></div>
                <?php if (isset($_SESSION['errors']['captcha'])) : ?>
                    <p class="text-xs text-white"><?php echo $_SESSION['errors']['captcha']; ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex justify-center mt-6">
            <button class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-pink-500 rounded-md hover:bg-pink-700 focus:outline-none focus:bg-gray-600" type="submit" value="Envoyer">Submit</button>
        </div>
    </form>
    <div id="error-container"></div>
</body>
</html>