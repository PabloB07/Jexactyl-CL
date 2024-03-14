import React from 'react';
import { ServerContext } from '@/state/server';
import ScreenBlock from '@/components/elements/ScreenBlock';
import ServerInstallSvg from '@/assets/images/server_installing.svg';
import ServerErrorSvg from '@/assets/images/server_error.svg';
import ServerRestoreSvg from '@/assets/images/server_restore.svg';

export default () => {
    const status = ServerContext.useStoreState((state) => state.server.data?.status || null);
    const isTransferring = ServerContext.useStoreState((state) => state.server.data?.isTransferring || false);
    const isNodeUnderMaintenance = ServerContext.useStoreState(
        (state) => state.server.data?.isNodeUnderMaintenance || false,
    );

    return status === 'installing' || status === 'install_failed' || status === 'reinstall_failed' ? (
        <ScreenBlock
            title={'Instalador en ejecución'}
            image={ServerInstallSvg}
            message={'Su servidor debería estar listo pronto. Vuelva a intentarlo en unos minutos.'}
        />
    ) : status === 'suspended' ? (
        <ScreenBlock
            title={'Servidor suspendido'}
            image={ServerErrorSvg}
            message={'Este servidor está suspendido y no puede ser accedido.'}
        />
    ) : isNodeUnderMaintenance ? (
        <ScreenBlock
            title={'Nodo en modomantención'}
            image={ServerErrorSvg}
            message={'El nodo de este servidor se encuentra actualmente en mantenimiento.'}
        />
    ) : (
        <ScreenBlock
            title={isTransferring ? 'Transferring' : 'Restoring from Backup'}
            image={ServerRestoreSvg}
            message={
                isTransferring
                    ? 'Su servidor se está transfiriendo a un nuevo nodo; vuelva a consultarlo más tarde.'
                    : 'Su servidor se está restaurando a partir de una copia de seguridad. Vuelva a comprobarlo en unos minutos.'
            }
        />
    );
};
