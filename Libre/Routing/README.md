#Routing

## Objectifs

Nous souhaitons un objet qui permet de lier une URI à un Controller, une Action de Controller, et de Params d'Action.
Cet objet pourra être intégré dans un objet FrontController.

## Analyses

Nous souhaitons un objet *Router* qui puisse comparée l'*URI* courante à une collection de *Route* contenues dans *RoutesCollection*.
Pour ce faire il va donc comparé l'*URI* courante à un ensemble de contraintes de route (*RouteConstraint*) pour déterminée
si l'*URI* est valide. Lorsqu'il s'avére que l'*URI* est valide, l'*URI* est transmise ensuite à un *UriParser* qui appliquera
des contraintes de Segments (*SegmentConstraint*) qui détermineront alors qu'elle est le *Controller*, L'*Action* et les *Params*
de la route courante si les segments de l'*URI* valide toutes les contraintes de segment (*SegmentConstraint*).

## Exemples

### Route

