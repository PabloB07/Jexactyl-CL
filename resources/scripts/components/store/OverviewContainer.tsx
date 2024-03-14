import React from 'react';
import { useStoreState } from 'easy-peasy';
import useWindowDimensions from '@/plugins/useWindowDimensions';
import ResourceBar from '@/components/elements/store/ResourceBar';
import StoreBanner from '@/components/elements/store/StoreBanner';
import PageContentBlock from '@/components/elements/PageContentBlock';

export default () => {
    const { width } = useWindowDimensions();
    const username = useStoreState((state) => state.user.data!.username);

    return (
        <PageContentBlock title={'Versión general de la tienda'}>
            <div className={'flex flex-row items-center justify-between mt-10'}>
                {width >= 1280 && (
                    <div>
                        <h1 className={'text-6xl'}>Hola, {username}!</h1>
                        <h3 className={'text-2xl mt-2 text-neutral-500'}>👋 Bienvenido a la tienda.</h3>
                    </div>
                )}
                {width >= 1024 && <ResourceBar className={'w-full lg:w-[100%]'} />}
            </div>
            <div className={'lg:grid lg:grid-cols-3 gap-8 my-10'}>
                <StoreBanner
                    title={'Desear crear un servidor?'}
                    className={'bg-storeone'}
                    action={'Crear'}
                    link={'create'}
                />
                <StoreBanner
                    title={'Necesitas mas recursos?'}
                    className={'bg-storetwo'}
                    action={'Comprar Recursos'}
                    link={'resources'}
                />
                <StoreBanner
                    title={'te quedaste sin céditos?'}
                    className={'bg-storethree'}
                    action={'Comprar Créditos'}
                    link={'credits'}
                />
            </div>
        </PageContentBlock>
    );
};
