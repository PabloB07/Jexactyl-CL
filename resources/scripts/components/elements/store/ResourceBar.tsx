import tw from 'twin.macro';
import classNames from 'classnames';
import * as Icon from 'react-feather';
import styled from 'styled-components/macro';
import { megabytesToHuman } from '@/helpers';
import React, { useState, useEffect } from 'react';
import Spinner from '@/components/elements/Spinner';
import ContentBox from '@/components/elements/ContentBox';
import Tooltip from '@/components/elements/tooltip/Tooltip';
import StoreContainer from '@/components/elements/StoreContainer';
import { getResources, Resources } from '@/api/store/getResources';

const Wrapper = styled.div`
    ${tw`text-2xl flex flex-row justify-center items-center`};
`;

interface RowProps {
    className?: string;
    titles?: boolean;
}

interface BoxProps {
    title: string;
    description: string;
    icon: React.ReactElement;
    amount: number;
    toHuman?: boolean;
    suffix?: string;
}

export default ({ className, titles }: RowProps) => {
    const [resources, setResources] = useState<Resources>();

    useEffect(() => {
        getResources().then((resources) => setResources(resources));
    }, []);

    if (!resources) return <Spinner size={'large'} centered />;

    const ResourceBox = (props: BoxProps) => (
        <ContentBox title={titles ? props.title : undefined}>
            <Tooltip content={props.description}>
                <Wrapper>
                    {props.icon}
                    <span className={'ml-2'}>
                        {props.toHuman ? <span className={'sm'}>{megabytesToHuman(props.amount)}</span> : props.amount}
                    </span>
                    {props.suffix}
                </Wrapper>
            </Tooltip>
        </ContentBox>
    );

    return (
        <StoreContainer className={classNames(className, 'grid grid-cols-2 sm:grid-cols-7 gap-x-6 gap-y-2')}>
            <ResourceBox
                title={'Créditos'}
                description={'La cantidad de créditos que tienes disponible.'}
                icon={<Icon.DollarSign />}
                amount={resources.balance}
            />
            <ResourceBox
                title={'CPU'}
                description={'La cantidad de CPU (en %) que tienes disponible.'}
                icon={<Icon.Cpu />}
                amount={resources.cpu}
                suffix={'%'}
            />
            <ResourceBox
                title={'Memoria'}
                description={'La cantidad de RAM (en MB/GB) que tienes disponible.'}
                icon={<Icon.PieChart />}
                amount={resources.memory}
                toHuman
            />
            <ResourceBox
                title={'Disco'}
                description={'La cantidad de almacenamiento (en MB/GB) que tienes disponible.'}
                icon={<Icon.HardDrive />}
                amount={resources.disk}
                toHuman
            />
            <ResourceBox
                title={'Slots'}
                description={'La cantidad de servidores que tu puedes hacr.'}
                icon={<Icon.Server />}
                amount={resources.slots}
            />
            <ResourceBox
                title={'Puertos'}
                description={'La cantidad de puertos que puedes añadir a tus servidores.'}
                icon={<Icon.Share2 />}
                amount={resources.ports}
            />
            <ResourceBox
                title={'Backups'}
                description={'La cantidad de slots de backups que puedes adicionar a sus servidores.'}
                icon={<Icon.Archive />}
                amount={resources.backups}
            />
            <ResourceBox
                title={'Databases'}
                description={'La cantidad de espacios de base de datos que puede agregar a sus servidores.'}
                icon={<Icon.Database />}
                amount={resources.databases}
            />
        </StoreContainer>
    );
};
