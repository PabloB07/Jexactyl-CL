import tw from 'twin.macro';
import asModal from '@/hoc/asModal';
import React, { useContext } from 'react';
import ModalContext from '@/context/ModalContext';
import { Button } from '@/components/elements/button/index';
import CopyOnClick from '@/components/elements/CopyOnClick';

interface Props {
    apiKey: string;
}

const ApiKeyModal = ({ apiKey }: Props) => {
    const { dismiss } = useContext(ModalContext);

    return (
        <>
            <h3 css={tw`mb-6 text-2xl`}>Sus claves API</h3>
            <p css={tw`text-sm mb-6`}>
                La clave API que solicitó se muestra a continuación. Guárdelo en un lugar seguro, no será
                mostrado de nuevo.
            </p>
            <pre css={tw`text-sm bg-neutral-900 rounded py-2 px-4 font-mono`}>
                <CopyOnClick text={apiKey}>
                    <code css={tw`font-mono`}>{apiKey}</code>
                </CopyOnClick>
            </pre>
            <div css={tw`flex justify-end mt-6`}>
                <Button type={'button'} onClick={() => dismiss()}>
                    Cerrar
                </Button>
            </div>
        </>
    );
};

ApiKeyModal.displayName = 'ApiKeyModal';

export default asModal<Props>({
    closeOnEscape: false,
    closeOnBackground: false,
})(ApiKeyModal);
