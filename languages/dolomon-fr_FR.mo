��    S      �  q   L        m          `   �  	   �     �       #     +   ?  
   k     v          �     �     �     �     �     �     �     �     	     	     "	     (	     0	  ,   L	  M   y	     �	     �	     �	     �	     �	     
  	    
     *
  �   :
     �
     �
  	   �
     �
  4   �
  
   4     ?  Y   M  J   �     �     �  *   �  &   &  6   M  1   �  6   �  %   �  7     \   K  >   �     �  Z   �     I     M      k  ;   �  %   �  :   �  2   )  -   \  ,   �  :   �     �  )   �  	   #     -  i   6     �  '   �  ,   �  Q     ,   Y  /   �     �     �  g   �  e   W  A  �  m   �     m  �   �          '     F  +   `  4   �     �  
   �     �     �               1     8     @     L     c     z     �     �  	   �  !   �  3   �  r   �     i     �  	   �     �     �     �     �     �  �   �     �     �  	   �     �  6   �     %     8  c   K  X   �  
          *     #   J  B   n  C   �  ;   �  +   1  _   ]  \   �  =        X  b   a     �  /   �  *   �  J   #  )   n  N   �  7   �  ?     ;   _  E   �     �  )   �       
     {   *     �  #   �  6   �  S      :   o   ;   �      �      �   ~   !  |   �!         H              2   5   P         K       4       $   1   +          ,   -       R         8       C   S                 
              "                 E   #             7      *      @          6   M      3   %      D   0      >   B   Q   L         F      N       J          	   )       :   /   ?   &                            A   O   <      I   '      !   (   G   =               ;   9   .                        Accepts all the parameters of the <pre><code>[dolo]</code></pre> shortcode except <pre><code>id</code></pre>. Add a category Add a list of dolos before the categories blocks. Argument is a comma separated list of dolos id Add a tag Application id Application secret Cache expiration delay (in minutes) Can take the following optional parameters: Categories Category Choose type of widget Create a category Create a dolo Create a tag Dismiss this notice. Dolomon Dolomon URL Dolomon server URL Dolomon settings Dolos Dolos widget Extra Format: Have an optional parameter: Hit Ctrl+C then enter to copy the short link It seems that the Dolomon server URL is not set. Check your Dolomon settings. My dolomon categories My dolomon tags My dolos Name Name of the category Name of the tag New title Number of dolos Please note that the parameters don't cumulate. <pre><code>self</code></pre> is overloaded by any other parameter except <pre><code>button</code></pre> Save Changes Short Shortcode Shortcodes help Show a single dolo, formatted with those parameters: Show dolos Show my dolos Shows all the dolos, grouped by categories, with a filter field to quickly search a dolo. Shows the dolos of a category, a tag, or all, depending of the parameters. Tag Tags The category has been successfully created The dolo has been successfully created The id of the category. Will show something like that: The id of the tag. Will show something like that: The only mandatory parameter, choose the dolo to show. The tag has been successfully created Then the formatting stops at first match in that order: This format the rendered text with the text of your choice. %foo will be replaced like this: Those parameters will be used for the formatting of the dolos. Title: Transforms an URL into an URL provided by a Dolomon server, which creates visit statistics URL Unable to open cache file %s! Unable to register your settings You do not have sufficient permissions to access this page. You don't have the right permissions. Your dolomon settings are invalid. Please check and retry. Your settings has been successfully registered :-) add a class to the link to look like a button click on a link to copy it to your clipboard do no show the category name, print only the list of dolos filter https://framagit.org/framasoft/wp-dolomon mandatory optional returns a link to the dolomon URL. Formatted with those optional parameters (default to the dolomon URL): shortcode builder the content of the dolo's "extra" field the name of the category the dolo belongs to the name of the dolo or its target URL (like https://example.org) if it's unnamed the name of the dolo's tags, comma separated the other parameters works like described above the target URL of the dolo the visit counter of the dolo used to filter the dolos of the category: will show only the dolos that have at least one of those tags used to filter the dolos of the tag: will show only the dolos that belongs to one of those categories Project-Id-Version: Dolomon 0.1
Report-Msgid-Bugs-To: https://wordpress.org/support/plugin/dolomon
POT-Creation-Date: 2016-07-06 11:12:53+00:00
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
PO-Revision-Date: 2016-MO-DA HO:MI+ZONE
Last-Translator: Luc Didry <luc@framasoft.org>
 Accepte tous les paramètres du shortcode <pre><code>[dolo]</code></pre> excepté <pre><code>id</code></pre>. Ajouter une catégorie Ajoute une liste de dolos devant les blocs des catégories. Son argument est une liste d’identifiants de dolos séparés par des virgules Ajouter une étiquette Identifiant de l’application Secret de l’application Délai d’expiration du cache (en minutes) Peut prendre les paramètres facultatifs suivants : Catégories Catégorie Choississez le type de widget Créer une catégorie Créer un dolo Créer une étiquette Fermer Dolomon URL dolomon URL du serveur Dolomon Paramètres de Dolomon Dolos Widget de dolos Extra Format : Possède un paramètre facultatif Faites Ctrl+C puis Entrée pour copier le shortcode Il semblerait que l’URL du server Dolomon ne soit pas paramétrée. Vérifiez la configuration du plugin Dolomon Mes catégories dolomon Mes étiquettes dolomon Mes dolos Nom Nom de la catégorie Nom de l’étiquette Nouveau titre Nombre de dolos Veullez notez que ces paramètres ne se cumulent pas. <pre><code>self</code></pre> est surchargé par n’importe quel autre paramètre excepté <pre><code>button</code></pre> Enregistrer les modifications Lien dolomon Shortcode Aide pour les shortcodes Affiche un seul dolo, formaté avec ces paramètres : Afficher les dolos Afficher mes dolos Affiche tous les dolos, groupés par catégories, avec un filtre pour rechercher rapidement un dolo Affiche les dolos d’une catégorie, d’une étiquette ou tous, selon les paramètres. Étiquette Étiquettes La catégorie a été créée avec succès Le dolo a été créé avec succès L’identifiant de la catégorie. Affichera quelque chose comme : L’identifiant de l’étiquette. Affichera quelque chose comme : Le seul paramètre obligatoire, choiit le dolo à afficher. L’étiquette a été créée avec succès Ensuite le formattag s’arrète à la première correspondance de paramètre dans cet ordre : Ceci formate le texte rendu avec le texte de votre choix. %truc sera remplacé comme suit : Ces paramètres seront utilisés pour le formatage des dolos. Titre : Transforme une URL en une URL fournie par un serveur Dolomon, qui crée des statistiques de visite URL Impossible d’ouvrir le fichier de cache %s ! Impossible d’enregistrer vos paramètres Vous n’avez pas les permissions suffisantes pour accéder à cette page. Vous n’avez pas les bonnes permissions. Vos paramètres pour Dolomon sont invalides. Veuillez vérifier et réessayer. Vos paramètres ont été enregistrés avec succès :-) ajoute une classe au lien pour le faire ressembler à un bouton cliquez sur un lien pour le copier dans votre presse-papier n’affiche pas le nom de la catégorie, seulement la liste des dolos filtrer https://framagit.org/framasoft/wp-dolomon obligatoire facultatif retourne un lien vers l’URL dolomon. Formaté par ces paramètres facultatifs (par défaut, le texte est l’URL dolomon) constructeur de shortcode le contenu du champ "extra" du dolo le nom de la catégorie à laquelle appartient le dolo le nom du dolo ou son URL cible (comme https://example.org) s’il n’a pas de nom le nom des étiquettes du dolo, séparés par des virgules les autres paramètres fonctionnent comme décrit plus haut l’URL cible du dolo le compteur de visite du dolo utilisé pour filtrer les dolos de la catégorie : n’affichera que les dolos qui possèdent au moins une de ces étiquettes utilisé pour filtrer les dolos de l’étiquette : n’affichera que les dolos qui appartiennent à une de ces catégories 