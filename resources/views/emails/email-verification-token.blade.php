@component('mail::message')
# Code de vérification de l'email

Votre code de vérification à 6 chiffres est :

**{{ $token }}**

Ce code expirera dans 10 minutes.

Merci,<br>
L'équipe de PMHCity
@endcomponent