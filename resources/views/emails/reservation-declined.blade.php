@component('mail::message')
# Réservation refusée

Bonjour {{ $user->name }},

Nous sommes désolés de vous informer que votre réservation n° **{{ $reservation->id }}** pour l'article **{{ $reservation->product->name }}** a été refusée.

---

**Ne vous découragez pas !**  
Nous vous invitons à découvrir les autres produits et offres disponibles sur notre plateforme.  

---

Merci d'utiliser notre service !  
L'équipe **PMHCity**

@endcomponent