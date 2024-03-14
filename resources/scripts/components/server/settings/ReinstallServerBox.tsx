import tw from 'twin.macro';
import { ApplicationStore } from '@/state';
import { httpErrorToHuman } from '@/api/http';
import { ServerContext } from '@/state/server';
import React, { useEffect, useState } from 'react';
import { Actions, useStoreActions } from 'easy-peasy';
import { Dialog } from '@/components/elements/dialog';
import reinstallServer from '@/api/server/reinstallServer';
import { Button } from '@/components/elements/button/index';
import TitledGreyBox from '@/components/elements/TitledGreyBox';

export default () => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const [modalVisible, setModalVisible] = useState(false);
    const { addFlash, clearFlashes } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const reinstall = () => {
        clearFlashes('settings');
        reinstallServer(uuid)
            .then(() => {
                addFlash({
                    key: 'settings',
                    type: 'success',
                    message: 'Su servidor ha comenzado el proceso de reinstalación.',
                });
            })
            .catch((error) => {
                console.error(error);

                addFlash({
                    key: 'settings',
                    type: 'danger',
                    message: httpErrorToHuman(error),
                });
            })
            .then(() => setModalVisible(false));
    };

    useEffect(() => {
        clearFlashes();
    }, []);

    return (
        <TitledGreyBox title={'Reinstalar el servidor'} css={tw`relative`}>
            <Dialog.Confirm
                open={modalVisible}
                title={'Confirme la reinstalación del servidor'}
                confirm={'Si, reinstalar el servidor'}
                onClose={() => setModalVisible(false)}
                onConfirmed={reinstall}
            >
                Su servidor se detendrá y es posible que algunos archivos se eliminen o modifiquen durante este tiempo.
                proceso, ¿estás seguro de que quieres continuar?
            </Dialog.Confirm>
            <p css={tw`text-sm`}>
                Reinstalar su servidor lo detendrá y luego volverá a ejecutar el script de instalación que
                inicialmente lo definió anteriormente.
                <strong css={tw`font-medium`}>
                    Algunos archivos pueden eliminarse o modificarse durante este proceso, haga una copia de seguridad de sus datos
                    Antes de continuar.
                </strong>
            </p>
            <div css={tw`mt-6 text-right`}>
                <Button.Danger variant={Button.Variants.Secondary} onClick={() => setModalVisible(true)}>
                    Reinstalar el servidor
                </Button.Danger>
            </div>
        </TitledGreyBox>
    );
};
