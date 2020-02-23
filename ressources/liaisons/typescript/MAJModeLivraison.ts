export class MAJModeLivraison{

    private executerAjax_lier:any = null;

    public constructor() {
        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    private static retournerResultat(data, textStatus, jqXHR):void{
        let refSelect = $('#choixLivraison');
        let refParent = refSelect.closest('.panier__livraison');
        let refRecapPrix = $('.panier__recap');

        $(refParent).find('.montantAjax').text(JSON.parse(data)['montantLivraison']);
        $(refParent).siblings('.panier__totalTotal').find('.montantAjax').text(JSON.parse(data)['totalTransaction']);

        let chaineOptionSelectLivraison = '[value="'+JSON.parse(data)['typeLivraison']+'"]';

        refSelect.find('option').removeAttr('selected');
        if (JSON.parse(data)['typeLivraison']=='gratuit') {
            refSelect.find(chaineOptionSelectLivraison).removeClass('displayNone');
        }
        else {
            if (refSelect.find(chaineOptionSelectLivraison).hasClass('displayNone')==false) {
                refSelect.find(chaineOptionSelectLivraison).removeClass('displayNone');
            }
        }
        //refSelect.find(chaineOptionSelectLivraison).attr('selected', 'selected');

        $(refRecapPrix).find('.panier__recapSection_total').find('span').text(JSON.parse(data)['totalTransaction']+" (avec taxes)");
    }

    private executerAjax():void
    {
        let select = $('.choixLivraison');
        $.ajax({
            url: 'index.php?controleur=panier&action=majModeLivraison',
            type: 'GET',
            data: 'choixLivraison='+select.val()
        })
            .done(function(data, textStatus, jqXHR) {
                MAJModeLivraison.retournerResultat(data, textStatus, jqXHR);
            })
    }

    private initialiser():void {
        let selectModeLivraison = document.querySelector('#choixLivraison');
        if (selectModeLivraison!=null) {
            selectModeLivraison.addEventListener('change', this.executerAjax_lier);
        }
    }
}

