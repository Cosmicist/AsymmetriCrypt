AsymmetriCrypt - Simple PHP public key cryptography
===================================================

Usage
-----

```php
<?php

use AsymmetriCrypt\Crypter;
use AsymmetriCrypt\Key\PublicKey;
use AsymmetriCrypt\Key\PrivateKey;

// Create a private key
$priv = Crypter::createPrivateKey();

// Load a private key
$priv = Crypter::loadPrivateKey("file:///path/to/key.pem");
// or
$priv = new PrivateKey("file:///path/to/key.pem");

// Get derived public key
$pub = $priv->getPublicKey();

// Load a public key
$pub = Crypter::loadPublicKey("file:///path/to/key.pub");
// or
$pub = new PublicKey("file:///path/to/key.pub");

// Encrypt data
$encrypted = Crypter::encrypt("data to encrypt", $pub);

// Decrypt data
$decrypted = Crypter::decrypt($encrypted, $priv);
```

Docs
----

I'm still working on a detailed documentation, but I don't have an ETA.