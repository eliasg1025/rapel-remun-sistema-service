import React from 'react';
import { Tabs } from 'antd';
import { PasosIniciales } from './PasosIniciales';
import { Resultados } from './Resultados';

export const Panel = ({ bono }) => {
    return (
        <Tabs defaultActiveKey="1">
            <Tabs.TabPane tab="Ejecución" key="1">
                <Resultados bono={bono} />
            </Tabs.TabPane>
            <Tabs.TabPane tab="Configuración" key="2">
                <PasosIniciales bono={bono} />
            </Tabs.TabPane>
        </Tabs>
    );
}
