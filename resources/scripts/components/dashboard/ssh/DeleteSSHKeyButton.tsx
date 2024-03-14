import tw from 'twin.macro';
import * as Icon from 'react-feather';
import React, { useState } from 'react';
import Code from '@/components/elements/Code';
import { useFlashKey } from '@/plugins/useFlash';
import { Dialog } from '@/components/elements/dialog';
import { deleteSSHKey, useSSHKeys } from '@/api/account/ssh-keys';

export default ({ name, fingerprint }: { name: string; fingerprint: string }) => {
    const { clearAndAddHttpError } = useFlashKey('account');
    const [visible, setVisible] = useState(false);
    const { mutate } = useSSHKeys();

    const onClick = () => {
        clearAndAddHttpError();

        Promise.all([
            mutate((data) => data?.filter((value) => value.fingerprint !== fingerprint), false),
            deleteSSHKey(fingerprint),
        ]).catch((error) => {
            mutate(undefined, true).catch(console.error);
            clearAndAddHttpError(error);
        });
    };

    return (
        <>
            <Dialog.Confirm
                open={visible}
                title={'Apagar clave SSH'}
                confirm={'Eliminar Clave'}
                onConfirmed={onClick}
                onClose={() => setVisible(false)}
            >
                Remover la clave <Code>{name}</Code> SSH será invalidado y desechado en todo el panel.
            </Dialog.Confirm>
            <button css={tw`ml-4 p-2 text-sm`} onClick={() => setVisible(true)}>
                <Icon.Trash css={tw`text-neutral-400 hover:text-red-400 transition-colors duration-150`} />
            </button>
        </>
    );
};
