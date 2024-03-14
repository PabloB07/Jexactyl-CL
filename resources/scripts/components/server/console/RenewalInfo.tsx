import React, { useState } from 'react';
import useFlash from '@/plugins/useFlash';
import { httpErrorToHuman } from '@/api/http';
import { useStoreState } from '@/state/hooks';
import { ServerContext } from '@/state/server';
import renewServer from '@/api/server/renewServer';
import { Dialog } from '@/components/elements/dialog';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';

export default () => {
    const [open, setOpen] = useState(false);
    const { addFlash, clearFlashes } = useFlash();
    const [loading, setLoading] = useState(false);
    const store = useStoreState((state) => state.storefront.data!);
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const renewal = ServerContext.useStoreState((state) => state.server.data!.renewal);

    const doRenewal = () => {
        setLoading(true);
        clearFlashes('console:share');

        renewServer(uuid)
            .then(() => {
                setOpen(false);
                setLoading(false);

                addFlash({
                    key: 'console:share',
                    type: 'success',
                    message: 'El servidor fue renovado.',
                });
            })
            .catch((error) => {
                setOpen(false);
                setLoading(false);

                console.log(httpErrorToHuman(error));
                addFlash({
                    key: 'console:share',
                    type: 'danger',
                    message: 'No se puede renovar su servidor. ¿Estás seguro de que tienes suficientes créditos??',
                });
            });
    };
    return (
        <>
            <Dialog.Confirm
                open={open}
                onClose={() => setOpen(false)}
                title={'Confirma para renovar tu servidor'}
                onConfirmed={() => doRenewal()}
            >
                <SpinnerOverlay visible={loading} />
                Se te será cobrado {store.renewals.cost} créditos para añadir {store.renewals.days} dias para su próxima vencida.
            </Dialog.Confirm>
            en {renewal} días{' '}
            <span className={'text-blue-500 text-sm cursor-pointer'} onClick={() => setOpen(true)}>
                {'('}Renovar{')'}
            </span>
        </>
    );
};
