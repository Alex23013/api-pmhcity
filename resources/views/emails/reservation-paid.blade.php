@component('mail::message')
# Confirmation de paiement

Bonjour {{ $reservation->buyer->name ?? '' }},

Nous vous confirmons que votre paiement pour la réservation n° **{{ $reservation->id }}** a bien été reçu.

---

**Détails de votre commande :**

- **Produit :** {{ $reservation->product->name }}
- **Prix unitaire :** {{ number_format($reservation->product->price, 2) }} €
- **Quantité :** {{ $reservation->quantity }}
- **Frais de livraison :** {{ number_format($deliveryPrice, 2) }} €
- **Total payé :** {{ number_format($reservation->price, 2) }} €

---

Merci pour votre achat !  
L'équipe de **PMHCity**
@endcomponent