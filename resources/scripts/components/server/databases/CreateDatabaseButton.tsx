import tw from 'twin.macro';
import { object, string, setLocale } from 'yup';
import { pt } from 'yup-locales';
import React, { useState } from 'react';
import useFlash from '@/plugins/useFlash';
import { httpErrorToHuman } from '@/api/http';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import Field from '@/components/elements/Field';
import { Form, Formik, FormikHelpers } from 'formik';
import { Button } from '@/components/elements/button/index';
import FlashMessageRender from '@/components/FlashMessageRender';
import createServerDatabase from '@/api/server/databases/createServerDatabase';

interface Values {
    databaseName: string;
    connectionsFrom: string;
}

export default () => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { addError, clearFlashes } = useFlash();
    const [visible, setVisible] = useState(false);

    setLocale(pt);

    const appendDatabase = ServerContext.useStoreActions((actions) => actions.databases.appendDatabase);

    const schema = object().shape({
        databaseName: string()
            .required('Se debe proporcionar un nombre de base de datos.')
            .min(3, 'El nombre de la Base de Datos debe tener al menos 3 caracteres.')
            .max(48, 'El nombre de la base de datos no debe exceder los 48 caracteres.')
            .matches(
                /^[\w\-.]{3,48}$/,
                'El nombre de la Base de Datos debe contener únicamente caracteres alfanuméricos, guiones bajos, guiones y/o puntos.',
            ),
        connectionsFrom: string().matches(/^[\w\-/.%:]+$/, 'Se debe proporcionar una dirección de host válida.'),
    });

    const submit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes('database:create');
        createServerDatabase(uuid, {
            databaseName: values.databaseName,
            connectionsFrom: values.connectionsFrom || '%',
        })
            .then((database) => {
                appendDatabase(database);
                setVisible(false);
            })
            .catch((error) => {
                addError({
                    key: 'database:create',
                    message: httpErrorToHuman(error),
                });
                setSubmitting(false);
            });
    };

    return (
        <>
            <Formik
                onSubmit={submit}
                initialValues={{ databaseName: '', connectionsFrom: '' }}
                validationSchema={schema}
            >
                {({ isSubmitting, resetForm }) => (
                    <Modal
                        visible={visible}
                        dismissable={!isSubmitting}
                        showSpinnerOverlay={isSubmitting}
                        onDismissed={() => {
                            resetForm();
                            setVisible(false);
                        }}
                    >
                        <FlashMessageRender byKey={'database:create'} css={tw`mb-6`} />
                        <h2 css={tw`text-2xl mb-6`}>Crear nuevo database</h2>
                        <Form css={tw`m-0`}>
                            <Field
                                type={'string'}
                                id={'database_name'}
                                name={'databaseName'}
                                label={'Nombre de database'}
                                description={'Un nombre descriptivo para su instancia de base de datos.'}
                            />
                            <div css={tw`mt-6`}>
                                <Field
                                    type={'string'}
                                    id={'connections_from'}
                                    name={'connectionsFrom'}
                                    label={'Conexiones de'}
                                    description={
                                        'Desde donde se deben permitir las conexiones. Déjelo en blanco para permitir conexiones desde cualquier lugar.'
                                    }
                                />
                            </div>
                            <div css={tw`flex flex-wrap justify-end mt-6`}>
                                <Button
                                    type={'button'}
                                    variant={Button.Variants.Secondary}
                                    css={tw`w-full sm:w-auto sm:mr-2`}
                                    onClick={() => setVisible(false)}
                                >
                                    Cancelar
                                </Button>
                                <Button css={tw`w-full mt-4 sm:w-auto sm:mt-0`} type={'submit'}>
                                    Crear database
                                </Button>
                            </div>
                        </Form>
                    </Modal>
                )}
            </Formik>
            <Button onClick={() => setVisible(true)}>Nuevo database</Button>
        </>
    );
};
