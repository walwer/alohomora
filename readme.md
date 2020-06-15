# Alohomora Library
This library can be used to encrypt and decrypt data. 

The main idea was to create a lib that will chunk the data and encrypt each to provide secure solution.

***
### Encryption
This process encrypts given string  - splits it into chunks and then cipher each with specified public key.
If you want to encode an array you must use PHP builtin `json_encode` function.

### Decryption
This process should decrypt 
***

## How to use it

### Initialize the lib
Creating an instance with constructor 

`$alohomora = new Alohomora()`

### Encrypt
1. Set the entry data to encode

`$alohomora->setEntry('Lorem ipsum dolor sit amet')`

2. Set output data

`$alohomora->setOutputDirectory(__DIR__.'/enc');
 $alohomora->setFileName('encrypted');`
 
 In this step you set a target directory by `setOutputDirectory()` and the name of directory that will hold the encrypted data by `setFileName()`

3. Encrypt your data

Here you should've prepared your public key string to encrypt your entry

`$alohomora->encryptEntry($publicKey);`

