<?php

namespace Tests\Unit\Filament\Resources\Contractors;

use Tests\TestCase;
use App\Filament\Resources\Contractors\ContractorResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;

class ContractorResourceTest extends TestCase
{
    public function testFormSchemaNameField()
    {
        $form = ContractorResource::form(Form::make());
        $nameField = $form->getSchema()[0];

        $this->assertEquals('name', $nameField->getName());
        $this->assertTrue($nameField->isRequired());
        $this->assertEquals('Name', $nameField->getLabel());
    }

    public function testFormSchemaEmailField()
    {
        $form = ContractorResource::form(Form::make());
        $emailField = $form->getSchema()[1];

        $this->assertEquals('email', $emailField->getName());
        $this->assertTrue($emailField->isRequired());
        $this->assertEquals('Email', $emailField->getLabel());
        $this->assertTrue($emailField->hasRule('email'));
    }

    public function testFormSchemaPhoneField()
    {
        $form = ContractorResource::form(Form::make());
        $phoneField = $form->getSchema()[2];

        $this->assertEquals('phone', $phoneField->getName());
        $this->assertFalse($phoneField->isRequired());
        $this->assertEquals('Phone Number', $phoneField->getLabel());
    }

    public function testFormSchemaAddressField()
    {
        $form = ContractorResource::form(Form::make());
        $addressField = $form->getSchema()[3];

        $this->assertEquals('address', $addressField->getName());
        $this->assertEquals(5, $addressField->getRows());
        $this->assertEquals('Address', $addressField->getLabel());
    }

    public function testTableSchemaNameColumn()
    {
        $table = ContractorResource::table(Table::make());
        $nameColumn = $table->getColumns()[0];

        $this->assertEquals('name', $nameColumn->getName());
        $this->assertTrue($nameColumn->isSortable());
    }

    public function testTableSchemaEmailColumn()
    {
        $table = ContractorResource::table(Table::make());
        $emailColumn = $table->getColumns()[1];

        $this->assertEquals('email', $emailColumn->getName());
    }

    public function testTableSchemaPhoneColumn()
    {
        $table = ContractorResource::table(Table::make());
        $phoneColumn = $table->getColumns()[2];

        $this->assertEquals('phone', $phoneColumn->getName());
    }

    public function testTableSchemaAddressColumn()
    {
        $table = ContractorResource::table(Table::make());
        $addressColumn = $table->getColumns()[3];

        $this->assertEquals('address', $addressColumn->getName());
        $this->assertEquals(50, $addressColumn->getLimit());
    }
}
