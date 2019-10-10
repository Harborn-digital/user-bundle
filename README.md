# Connect Holland User Bundle

User bundle for Symfony 4 projects

## Functionality

This bundle will be extendible and provide:

- Simple registration form
- Simple login form
- Ability to 'switch on' MFA
- A Command to create users with their roles
- An e-mail message with a secure link to complete account registration
- Recover password functionality
- Being API accessable
- Ability to 'switch on' OAuth (Google/GitHub/Facebook/etc)

## Environment

Set the environment variables to be able to send e-mails.

```dotenv
USERBUNDLE_FROM_EMAILADDRESS=example@example.com
```

## OAuth

To be able using OAuth login require _HWIOAuth_ bundle.

```bash
composer require hwi/oauth-bundle php-http/guzzle6-adapter:^1.0 php-http/httplug-bundle
```

Add environment variables to enable a specific OAuth provider (resource). E.g. for google:
```dotenv
USERBUNDLE_OAUTH_GOOGLE_ID=xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com
USERBUNDLE_OAUTH_GOOGLE_SECRET=XXXXXXXXXXX-xx_xx_xxxxx
USERBUNDLE_OAUTH_GOOGLE_SCOPE='email profile'
# Options specific for the provider can be added in a json encoded string like below.
USERBUNDLE_OAUTH_GOOGLE_OPTIONS={"hd": "connectholland.nl"}
```
