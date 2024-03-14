import tw from 'twin.macro';
import { breakpoint } from '@/theme';
import * as Icon from 'react-feather';
import { Link } from 'react-router-dom';
import useFlash from '@/plugins/useFlash';
import styled from 'styled-components/macro';
import React, { useState, useEffect } from 'react';
import Spinner from '@/components/elements/Spinner';
import { Button } from '@/components/elements/button';
import { Dialog } from '@/components/elements/dialog';
import { getCosts, Costs } from '@/api/store/getCosts';
import purchaseResource from '@/api/store/purchaseResource';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import PurchaseBox from '@/components/elements/store/PurchaseBox';
import PageContentBlock from '@/components/elements/PageContentBlock';

const Container = styled.div`
    ${tw`flex flex-wrap`};

    & > div {
        ${tw`w-full`};

        ${breakpoint('sm')`
      width: calc(50% - 1rem);
    `}

        ${breakpoint('md')`
      ${tw`w-auto flex-1`};
    `}
    }
`;

export default () => {
    const [open, setOpen] = useState(false);
    const [costs, setCosts] = useState<Costs>();
    const [resource, setResource] = useState('');
    const { addFlash, clearFlashes, clearAndAddHttpError } = useFlash();

    useEffect(() => {
        getCosts().then((costs) => setCosts(costs));
    }, []);

    const purchase = (resource: string) => {
        clearFlashes('store:resources');

        purchaseResource(resource)
            .then(() => {
                setOpen(false);
                addFlash({
                    type: 'success',
                    key: 'store:resources',
                    message: 'El recurso ha sido agregado a su cuenta.',
                });
            })
            .catch((error) => clearAndAddHttpError({ key: 'store:resources', error }));
    };

    if (!costs) return <Spinner size={'large'} centered />;

    return (
        <PageContentBlock
            title={'Comprar recursos'}
            description={'Compra más recursos para agregar a tu servidor.'}
            showFlashKey={'store:resources'}
        >
            <SpinnerOverlay size={'large'} visible={open} />
            <Dialog.Confirm
                open={open}
                onClose={() => setOpen(false)}
                title={'Confirmar selección de recursos'}
                confirm={'Continuar'}
                onConfirmed={() => purchase(resource)}
            >
                ¿Está seguro de que desea comprar este recurso ({resource})? Esto gastará los créditos de su cuenta y
                agregará el recurso. Esta no es una transacción reversible.
            </Dialog.Confirm>
            <Container className={'lg:grid lg:grid-cols-4 my-10 gap-8'}>
                <PurchaseBox
                    type={'CPU'}
                    amount={50}
                    suffix={'%'}
                    cost={costs.cpu}
                    setOpen={setOpen}
                    icon={<Icon.Cpu />}
                    setResource={setResource}
                    description={'Compre CPU para mejorar los tiempos de carga y el rendimiento del servidor.'}
                />
                <PurchaseBox
                    type={'Memory'}
                    amount={1}
                    suffix={'GB'}
                    cost={costs.memory}
                    setOpen={setOpen}
                    icon={<Icon.PieChart />}
                    setResource={setResource}
                    description={'Compre RAM para mejorar el rendimiento general del servidor.'}
                />
                <PurchaseBox
                    type={'Disk'}
                    amount={1}
                    suffix={'GB'}
                    cost={costs.disk}
                    setOpen={setOpen}
                    icon={<Icon.HardDrive />}
                    setResource={setResource}
                    description={'Compre un disco para almacenar más archivos.'}
                />
                <PurchaseBox
                    type={'Slots'}
                    amount={1}
                    cost={costs.slots}
                    setOpen={setOpen}
                    icon={<Icon.Server />}
                    setResource={setResource}
                    description={'Compre una ranura de servidor para poder implementar un nuevo servidor.'}
                />
            </Container>
            <Container className={'lg:grid lg:grid-cols-4 my-10 gap-8'}>
                <PurchaseBox
                    type={'Ports'}
                    amount={1}
                    cost={costs.ports}
                    setOpen={setOpen}
                    icon={<Icon.Share2 />}
                    setResource={setResource}
                    description={'Compre un puerto de red para agregarlo a un servidor.'}
                />
                <PurchaseBox
                    type={'Backups'}
                    amount={1}
                    cost={costs.backups}
                    setOpen={setOpen}
                    icon={<Icon.Archive />}
                    setResource={setResource}
                    description={'Compra una copia de seguridad para mantener tus datos seguros.'}
                />
                <PurchaseBox
                    type={'Databases'}
                    amount={1}
                    cost={costs.databases}
                    setOpen={setOpen}
                    icon={<Icon.Database />}
                    setResource={setResource}
                    description={'Compre una base de datos para obtener y configurar datos.'}
                />
                <TitledGreyBox title={'Como usar los recursos'}>
                    <p className={'font-semibold'}>Agregar a un servidor existente</p>
                    <p className={'texto-xs texto-gris-500'}>
                        Si tiene un servidor que ya está implementado, puede agregarle recursos yendo a
                        pestaña editar.
                    </p>
                    <p className={'font-semibold mt-1'}>Agregar a un nuevo servidor</p>
                    <p className={'texto-xs texto-gris-500'}>
                        Puede comprar recursos y agregarlos a un nuevo servidor desde la página de creación del
                        servidor.
                        a la que puedes acceder a través de la tienda.
                    </p>
                </TitledGreyBox>
            </Container>
            <div className={'flex justify-center items-center'}>
                <div className={'bg-auto bg-center bg-storeone p-4 m-4 rounded-lg'}>
                    <div className={'text-center bg-gray-900 bg-opacity-75 p-4'}>
                        <h1 className={'text-4xl'}>¿Listo para empezar?</h1>
                        <Link to={'/store/create'}>
                            <Button.Text className={'w-full mt-4'}>Crear un Servidor, ahora!</Button.Text>
                        </Link>
                    </div>
                </div>
            </div>
        </PageContentBlock>
    );
};
