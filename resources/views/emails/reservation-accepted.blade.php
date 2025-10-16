@component('mail::message')
# Réservation acceptée

Bonjour {{ $user->name }},

Nous avons le plaisir de vous informer que votre réservation n° **{{ $reservation->id }}** pour l'article **{{ $reservation->product->name }}** a été acceptée !

---

**Détails de la réservation :**

- **Produit :** {{ $reservation->product->name }}
- **Quantité :** {{ $reservation->quantity }}
- **Prix unitaire :** {{ number_format($reservation->product->price, 2) }} €

---

Vous pouvez maintenant procéder au paiement pour finaliser votre commande.

Merci d'utiliser notre service !  
L'équipe **PMHCity**

@endcomponent