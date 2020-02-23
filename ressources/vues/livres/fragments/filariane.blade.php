
<div class="filAriane conteneur">
    <ul class="filAriane__liste tableOnly">
        <?php $intCpt = 0;?>
        @if(isset($_GET['idLivre'])&&count($filAriane)==2)
            @foreach($filAriane as $lien)
                <?php $intCpt++;?>
                @if($intCpt==2)<li class="filAriane__listeItem filAriane__3eNiveau grosseTableOnly">{{$refUnLivre->getTitreRaccourci()}}</li>
                @else<li class="filAriane__listeItem filAriane__{{$intCpt}}eNiveau"><a href="{{$lien['lien']}}" class="filAriane__lien"><span class="filAriane__lien_tx">{{$lien['titre']}}</span></a></li>
                @endif
            @endforeach
        @else
            @foreach($filAriane as $lien)
                <?php $intCpt++;?>
                @if($intCpt==3)<li class="filAriane__listeItem filAriane__{{$intCpt}}eNiveau grosseTableOnly">{{$refUnLivre->getTitreRaccourci()}}</li>
                @elseif($intCpt==2 && $nomPage=='Livres')<li class="filAriane__listeItem filAriane__{{$intCpt}}eNiveau">{{$lien['titre']}}</li>
                @else<li class="filAriane__listeItem filAriane__{{$intCpt}}eNiveau"><a href="{{$lien['lien']}}" class="filAriane__lien"><span class="filAriane__lien_tx">{{$lien['titre']}}</span></a></li>
                @endif
            @endforeach
        @endif
    </ul>
    <ul class="filAriane__liste mobileOnly">
        @if(isset($filAriane[1]['lien'])!=false)<li class="filAriane__listeItem filAriane__2eNiveau"><a href="{{$filAriane[1]['lien']}}">{{$filAriane[1]['titre']}}</a></li>
        @else <li class="filAriane__listeItem filAriane__1eNiveau"><a href="{{$filAriane[0]['lien']}}">{{$filAriane[0]['titre']}}</a></li>
        @endif
    </ul>
</div>