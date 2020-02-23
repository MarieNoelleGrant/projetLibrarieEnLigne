export class MAJQuantite{

    private executerAjax_lier = null;

    public constructor() {
        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    private static retournerResultat(data, textStatus, jqXHR, refISBN):void{
        let selectId = '#'+refISBN;
        let refParentPrix = $('.panier__resumeListe');
        let refRecapPrix = $('.panier__recap');


        $(selectId).closest('.itemPanier__quantite').siblings('.itemPanier__total').find('.montantAjax').text(JSON.parse(data)['prix_livreQte']);

        $(refParentPrix).find('.panier__sousTotal').find('.montantAjax').text(JSON.parse(data)['sousTotalTransaction']);
        $(refParentPrix).find('.panier__TPS').find('.montantAjax').text(JSON.parse(data)['taxesTransaction']);
        $(refParentPrix).find('.panier__livraison').find('.montantAjax').text(JSON.parse(data)['montantLivraison']);
        $(refParentPrix).find('.panier__totalTotal').find('.montantAjax').text(JSON.parse(data)['totalTransaction']);
        let chaineOptionSelectLivraison = '[value="'+JSON.parse(data)['typeLivraison']+'"]';

        $(refParentPrix).find('#choixLivraison').find('option').removeAttr('selected');
        if (JSON.parse(data)['typeLivraison']=='gratuit') {
            $(refParentPrix).find('#choixLivraison').find(chaineOptionSelectLivraison).removeClass('displayNone');
            $(refParentPrix).find('#choixLivraison').find('[value="standard"]').addClass('displayNone');
        }
        else {
            $(refParentPrix).find('#choixLivraison').find('[value="gratuit"]').addClass('displayNone');
            $(refParentPrix).find('#choixLivraison').find('[value="standard"]').removeClass('displayNone');

        }
        $(refParentPrix).find('#choixLivraison').find(chaineOptionSelectLivraison).attr('selected', 'selected');

        $(refRecapPrix).find('.panier__recapSection_qte').find('span').text(JSON.parse(data)['qteTotaleItems']+" items");
        $(refRecapPrix).find('.panier__recapSection_total').find('span').text(JSON.parse(data)['totalTransaction']+" (avec taxes)");
    }

    private executerAjax(evenement):void
    {
        let select = $(evenement.currentTarget);
        $.ajax({
            url: 'index.php?controleur=panier&action=majQuantite&isbn='+select.attr('id'),
            type: 'GET',
            data: 'choixQte='+select.val()
        })
            .done(function(data, textStatus, jqXHR) {
                MAJQuantite.retournerResultat(data, textStatus, jqXHR, select.attr('id'));
            })
    }

    private initialiser():void {
        let arrSelectQte = document.querySelectorAll('.selectQte');

        if (arrSelectQte.length>0) {
            for (let intCpt=0; intCpt<arrSelectQte.length; intCpt++) {
                arrSelectQte[intCpt].addEventListener('change', this.executerAjax_lier);
            }
        }

    }
}

