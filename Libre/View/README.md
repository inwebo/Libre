# Template system

## Objects
* View : Template + ViewObject
* Template : Sera parser par le parser et représente le résulta.
* Parser : Injecte des données du ViewObject dans une Template
* ViewObject : Service locator
* Task : Est une tache du Parser
* TasksCollection : Est l'ensemble du code métier du parser

## Tags
* {dump} : var_dump du view object courant
* {nopparser}{/nopparser} : Echappement
* {CONST_NAME} : La valeure de la constante CONST_NAME
* {incl=pathFile.php} : Inclus pathFile.php dans la vue courante
* {tpl=pathFile.php} : Inclus pathFile.php sous forme de template dans la vue
courante
* {$varName} : La valeur de $varName contenue dans le viewobject courant

