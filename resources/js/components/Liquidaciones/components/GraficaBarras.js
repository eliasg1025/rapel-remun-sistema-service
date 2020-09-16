import Axios from 'axios';
import React, { useState, useEffect } from 'react';
import { Bar } from 'react-chartjs-2';
import {Select, Table} from "antd";
import {empresa} from "../../../data/default.json";

export const GraficaBarras = () => {

    const [montosPorEstado, setMontosPorEstado] = useState({});
    const [montosPorAnio, setMontosPorAnio] = useState([]);
    const [filter, setFilter] = useState({
        empresa_id: 9
    });

    const columns = [
        {
            title: 'Año',
            dataIndex: 'anio'
        },
        {
            title: 'Pendientes',
            dataIndex: 'pendiente'
        },
        {
            title: 'Firmados',
            dataIndex: 'firmados'
        },
        {
            title: 'Para Pago',
            dataIndex: 'para_pago'
        },
        {
            title: 'Pagados',
            dataIndex: 'pagados'
        },
        {
            title: 'Total',
            dataIndex: 'total'
        },
    ]

    const data = () => {
        return {
            labels: ['Pendientes', 'Firmados', 'Para Pago', 'Pagados'],
            datasets: [
                {
                    label: ['Pendientes'],
                    data: [montosPorEstado?.pendiente || 0, montosPorEstado?.firmados || 0, montosPorEstado?.para_pago || 0, montosPorEstado?.pagados || 0],
                    backgroundColor:  ['rgba(1,0,102,0.7)', 'rgba(204,51,0,0.7)', 'rgba(244,180,0,0.7)', 'rgba(64,203,10,0.7)'],
                }
            ]
        }
    }

    const options = () => {
        return {
            title: {
                display: true,
                text: `Montos (S/.) por estado de liquidación`
            },
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                ],
            }
        }
    }

    useEffect(() => {
        Axios.get(`/api/finiquitos/montos-por-estado?empresa_id=${filter.empresa_id}`)
            .then(res => {
                console.log(res);
                setMontosPorEstado(res.data);
            })
            .catch(err => {
                console.error(err);
            });

        Axios.get(`/api/finiquitos/montos-por-estado-por-anio/${filter.empresa_id}`)
            .then(res => {
                console.log(res);
                setMontosPorAnio(res.data);
            })
            .catch(err => {
                console.error(err);
            })
    }, [filter]);

    return (
        <>
            <div className="row">
                <div className="col">

                </div>
            </div>
            <div className="row">
                <div className="col-md-6">
                    <Bar
                        data={data()} options={options()} height={200}
                    />
                </div>
                <div className="col-md-6">
                    <div className="row">
                        <div className="col">
                            <div className="card">
                                <div className="card-body">
                                    Empresa:<br />
                                    <Select
                                        value={filter.empresa_id} showSearch
                                        style={{ width: '100%' }} optionFilterProp="children"
                                        filterOption={(input, option) =>
                                            option.children
                                                .toLowerCase()
                                                .indexOf(input.toLowerCase()) >= 0
                                        }
                                        onChange={e => setFilter({ ...filter, empresa_id: e })}
                                    >
                                        {empresa.map(e => (
                                            <Select.Option value={e.id} key={e.id}>
                                                {`${e.id} - ${e.name}`}
                                            </Select.Option>
                                        ))}
                                    </Select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div className="row">
                        <div className="col">
                            <Table
                                dataSource={montosPorAnio}
                                columns={columns}
                                size="small"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
