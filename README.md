# Connect Holland User Bundle

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ConnectHolland/user-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ConnectHolland/user-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ConnectHolland/user-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ConnectHolland/user-bundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ConnectHolland/user-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ConnectHolland/user-bundle/build-status/master)

User bundle for Symfony 4 projects

## Functionality

This bundle will be extendible and provide:

- [x] Simple registration form
- [x] Simple login form
- [ ] Ability to 'switch on' MFA
- [x] A Command to create users with their roles
- [x] An e-mail message with a secure link to complete account registration
- [x] Recover password functionality
- [ ] Being API accessable
- [x] Ability to 'switch on' OAuth (Google/GitHub/Facebook/etc)

## Environment

Set the environment variables to be able to send e-mails.

```dotenv
USERBUNDLE_FROM_EMAILADDRESS=example@example.com
```

## Create a user

To create a new user run:

```bash
./bin/console connectholland:user:create example@example.com p@$$w0rd --role=ROLE_USER
```

## OAuth

To use OAuth login add environment variables to enable a specific OAuth provider (resource). E.g. for google:
```dotenv
USERBUNDLE_OAUTH_GOOGLE_ID=xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com
USERBUNDLE_OAUTH_GOOGLE_SECRET=XXXXXXXXXXX-xx_xx_xxxxx
USERBUNDLE_OAUTH_GOOGLE_SCOPE='email profile'
# Options specific for the provider can be added in a json encoded string like below.
USERBUNDLE_OAUTH_GOOGLE_OPTIONS={"hd": "connectholland.nl"}
```

## Security configuration example

```yaml
security:
    encoders:
        Symfony\Component\Security\Core\User\UserInterface:
            algorithm: auto

    providers:
        app_user_provider:
            entity:
                class: ConnectHolland\UserBundle\Entity\User
                property: email
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            guard:
                authenticators:
                    - ConnectHolland\UserBundle\Security\UserBundleAuthenticator
            logout:
                path: connectholland_user_logout
            oauth:
                use_forward: false
                resource_owners:
                    # The resource_owners routing postfixes are a composition of the firewall name and the resource name
                    google: connectholland_user_oauth_check_main_google
                    facebook: connectholland_user_oauth_check_main_facebook
                    linkedin: connectholland_user_oauth_check_main_linkedin
                    # etcetera
                login_path: connectholland_user_login
                failure_path: connectholland_user_login
                oauth_user_provider:
                    service: ConnectHolland\UserBundle\Security\OAuthUserProvider

    access_control:
        - { path: ^/inloggen, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/registreren, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/connect, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/connect, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/login/oauth-check, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, roles: [ROLE_OAUTH, ROLE_ADMIN ] }
```

## Extend User entity

If you want to extend the User entity, you should clone [User](https://github.com/ConnectHolland/user-bundle/blob/master/src/Entity/User.php) and add it as entity in your own project.
