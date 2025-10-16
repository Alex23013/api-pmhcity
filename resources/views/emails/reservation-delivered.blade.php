@component('mail::message')
# Livraison confirmée

Bonjour {{ $user->name }},

Nous avons le plaisir de vous informer que votre article **{{ $reservation->product->name }}** pour la réservation n° **{{ $reservation->id }}** a été livré avec succès.

---

**Détails de la réservation :**

- **Produit :** {{ $reservation->product->name }}
- **Quantité :** {{ $reservation->quantity }}
- **Date de livraison :** {{ $reservation->reservationSteps->where('reservation_status_id', 6)->first()?->created_at->format('d/m/Y H:i') ?? 'N/A' }}

---

Merci d'avoir choisi **PMHCity** !  
L'équipe PMHCity
@endcomponent