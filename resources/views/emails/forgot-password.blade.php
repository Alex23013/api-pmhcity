@component('mail::message')
# Bonjour {{ $name }},

Vous avez demandé une réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :

@component('mail::button', ['url' => $url, 'color' => 'cyan'])
Réinitialiser le mot de passe
@endcomponent

Ce lien expirera dans **10 minutes**.

Si vous avez des questions, n'hésitez pas à contacter notre équipe d'assistance à l'adresse suivante<br> [support@pmhcity.com](mailto:support@pmhcity.com).

Merci,<br>
**L'équipe de PMHCity**
@endcomponent
